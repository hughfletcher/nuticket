<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\FetchEmailJob;
use Tests\TestCase;
use Mockery as m;
use App\Events\TicketCreatedEvent;
use App\Events\ActionCreatedEvent;
use Faker\Factory as Faker;
use Illuminate\Support\Collection;
use App\Http\Requests\TicketUpdateRequest;

class FetchEmailJobTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->email = factory(App\Email::class)->make();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHandle()
    {
        $this->expectsEvents(App\Events\ActionCreatedEvent::class, App\Events\TicketCreatedEvent::class);
        $fetch = m::mock('App\Jobs\FetchEmailJob[getServer,getMessages,anonymousUser,createTicket,buildActions,processEmail]', [$this->email]);

        $messages = [m::mock('Fetch\Message'), m::mock('Fetch\Message'), m::mock('Fetch\Message')];
        $server = m::mock();
        $server->shouldReceive('getMessages')->once()->andReturn($messages);
        $server->shouldReceive('getServerString')->once();
        $server->shouldReceive('numMessages')->once();

        Log::shouldReceive('debug')->once();

        $fetch->shouldReceive('getServer')->once()->andReturn($server);
        $fetch->shouldReceive('anonymousUser')->times(3)->andReturn(collect());
        // $fetch->shouldReceive('getAssigned')->times(3)->andReturn(collect());

        $fetch->shouldReceive('createTicket')->times(3)->andReturn(collect(['ticket' => m::mock('App\Ticket')]), collect());

        $fetch->shouldReceive('buildActions')->times(3)->andReturn(collect());
        $fetch->shouldReceive('processEmail')->times(3);

        $parser = m::mock('App\Services\EmailParser');
        $parser->shouldReceive('parse')->times(3)->andReturn(collect());
        $user = m::mock('App\Contracts\Repositories\UserInterface');

        $fetch->handle($parser, $user);
    }

    public function testAnonymousUserWithAuthIdAcceptUnknownFalse()
    {
        $data = $this->createData('auth_id');
        $this->app['config']->set('mail.acceptunknown', false);
        $user = m::mock('App\Contracts\Repositories\UserInterface');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->anonymousUser($data, $user);
        $this->assertEquals($return, $data);
    }

    public function testAnonymousUserWithAuthIdAcceptUnknownTrue()
    {
        $data = $this->createData('auth_id');
        $this->app['config']->set('mail.acceptunknown', true);
        $user = m::mock('App\Contracts\Repositories\UserInterface');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->anonymousUser($data, $user);
        $this->assertEquals($return, $data);
    }

    public function testAnonymousUserWithNoAuthIdAcceptUnknownFalse()
    {
        $data = $this->createData('name', 'email');
        $this->app['config']->set('mail.acceptunknown', false);
        $user = m::mock('App\Contracts\Repositories\UserInterface');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->anonymousUser($data, $user);
        $this->assertEquals($return, $data);
    }

    public function testAnonymousUserWithNoAuthIdAcceptUnknownTrue()
    {
        $data = $this->createData('name', 'email');
        $this->app['config']->set('mail.acceptunknown', true);
        $user = m::mock('App\Contracts\Repositories\UserInterface');
        $auth = m::mock();
        $auth->id = 444;
        $user->shouldReceive('create')
            ->with(['display_name' => $data->get('name'), 'email' => $data->get('email')])
            ->once()
            ->andReturn($auth);
        $fetch = new FetchEmailJob($this->email);

        $return = $fetch->anonymousUser($data, $user);
        $data->put('auth_id', $auth->id);
        $this->assertEquals($return, $data);

    }

    public function testNewServer()
    {
        $fetch = new FetchEmailJob($this->email);
        $server = $fetch->newServer($this->email->mail_host, $this->email->mail_port, $this->email->mail_protocol);
        $this->assertInstanceOf('Fetch\Server', $server);

    }

    public function testGetServerSslTrue()
    {
        $this->email->mail_ssl = true;
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->getServer();
        $this->assertInstanceOf('Fetch\Server', $return);
        $this->assertTrue(strpos($return->getServerString(), "/novalidate-cert/ssl}") !== false);
    }

    public function testGetServerSslFalse()
    {
        $this->email->mail_ssl = false;
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->getServer();
        $this->assertInstanceOf('Fetch\Server', $return);
        $this->assertFalse(strpos($return->getServerString(), "/novalidate-cert/ssl}") !== false);
    }

    public function testBuildActionsBodyOnlyNoHours()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $data = $this->createData('ticket_id', 'body', 'auth_id');
        $this->runBuildActions($data);
    }


    public function testBuildActionsBodyOnlyWithHours()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $data = $this->createData('ticket_id', 'body', 'hours', 'auth_id');
        $this->runBuildActions($data, ['hours' => $data->get('hours')]);
    }

    public function testBuildActionsCommentTag()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $data = $this->createData('ticket_id', 'body', '_comment', 'auth_id');
        $this->runBuildActions($data, ['type' => 'comment']);
    }

    public function testBuildActionsClosedTag()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $data = $this->createData('ticket_id', 'body', '_closed', 'auth_id');
        $this->runBuildActions($data, ['type' => 'closed']);
    }

    public function testBuildActionsResolvedTag()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $data = $this->createData('ticket_id', 'body', '_resolved', 'auth_id');
        $this->runBuildActions($data, ['type' => 'resolved']);
    }

    public function testBuildActionsOpenTag()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $data = $this->createData('ticket_id', 'body', '_open', 'auth_id');
        $this->runBuildActions($data, ['type' => 'open']);
    }

    public function testBuildActionWithEditTags()
    {
        $data = $this->createData('ticket_id', 'auth_id', 'body', 'user_id', 'priority');
        $fetch = m::mock('App\Jobs\FetchEmailJob[validateAction, createEditAction]', [$this->email]);
        $fetch->shouldReceive('createEditAction')
            ->with(m::on(function ($arg) use ($data) {
                $this->assertEquals(
                    $data->merge(['user_id' => $data->get('auth_id'), '_user_id' => $data->get('user_id')])->toArray(),
                    $arg->toArray()
                );
                return true;
            }))
            ->once()
            ->andReturn(['action' => 'edit']);
        $actions = $fetch->buildActions($data);
        $this->assertCount(1, $actions);
        $this->assertEquals(['action' => 'edit'], $actions->first());
    }

    public function testBuildActionWithMultipleTags()
    {
        $this->expectsJobs(App\Jobs\ActionCreateJob::class);
        $fetch = m::mock('App\Jobs\FetchEmailJob[validateAction, createEditAction]', [$this->email]);
        $data = $this->createData('ticket_id', 'auth_id', 'body', 'user_id', 'priority', 'comment', 'reply', '_resolved', 'hours');
        $fetch->shouldReceive('validateAction')
            ->with(m::type('Illuminate\Support\Collection'))
            ->times(3)
            ->andReturn(true);
        $fetch->shouldReceive('createEditAction')
            ->with(m::on(function ($arg) use ($data) {
                $this->assertEquals(
                    $data->forget(['body', 'hours'])
                        ->merge(['user_id' => $data->get('auth_id'), '_user_id' => $data->get('user_id')])
                        ->toArray(),
                    $arg->toArray()
                );
                return true;
            }))
            ->once()
            ->andReturn(true);
        $actions = $fetch->buildActions($data);
        $this->assertCount(4, $actions);
    }

    protected function runBuildActions(Collection $data, $extra_attrs = [])
    {

        $base = array_merge([
            'source' => 'mail',
            'defer_event' => true,
            'ticket_id' => $data->get('ticket_id'),
            'type' => 'reply',
            'user_id' => $data->get('auth_id'),
            'body' => $data->get('body')
        ], $extra_attrs);
        $fetch = m::mock('App\Jobs\FetchEmailJob[validateAction, createEditAction]', [$this->email]);
        $fetch->shouldReceive('validateAction')
            ->with(m::on(function ($arg) use ($base) {
                $this->assertEquals($arg->toArray(), $base);
                return true;
            }))
            ->once()
            ->andReturn(true);
        $fetch->shouldReceive('createEditAction')
            ->with(m::type('Illuminate\Support\Collection'))
            ->once()
            ->andReturn(null);
        $actions = $fetch->buildActions($data);
        $this->assertCount(1, $actions);
    }

    public function testValidateActionFails()
    {
        $errors = m::mock();
        $errors->shouldReceive('all')->once()->andReturn([]);
        $validator = m::mock();
        $validator->shouldReceive('fails')->once()->andReturn(true);
        $validator->shouldReceive('errors')->once()->andReturn($errors);
        Validator::shouldReceive('make')->once()->andReturn($validator);
        Log::shouldReceive('debug')->once();
        $fetch = new FetchEmailJob($this->email);
        $check = $fetch->validateAction(collect());
        $this->assertFalse($check);
    }

    public function testValidateActionPasses()
    {
        // $errors = m::mock();
        // $errors->shouldReceive('all')->once()->andReturn([]);
        $validator = m::mock();
        $validator->shouldReceive('fails')->once()->andReturn(false);
        // $validator->shouldReceive('errors')->once()->andReturn($errors);
        Validator::shouldReceive('make')->once()->andReturn($validator);
        // Log::shouldReceive('debug')->once();
        $fetch = new FetchEmailJob($this->email);
        $check = $fetch->validateAction(collect());
        $this->assertTrue($check);
    }

    public function testCreateEditActionWithUserNoBodyValid()
    {
        $this->expectsJobs(App\Jobs\TicketUpdateJob::class);
        $data = $this->createData('ticket_id', 'auth_id', 'user_id', 'reply');
        $data->put('type', 'edit');
        $validator = m::mock();
        $validator->shouldReceive('fails')->once()->andReturn(false);
        Validator::shouldReceive('make')
            ->with($data->except('reply')->toArray(), TicketUpdateRequest::$rules)
            ->andReturn($validator);
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->createEditAction($data);
        $this->assertNull($return);
    }

    public function testCreateEditActionWithPriorityWithBodyValid()
    {
        $this->expectsJobs(App\Jobs\TicketUpdateJob::class);
        $data = $this->createData('ticket_id', 'auth_id', 'body', 'priority', '_comment');
        $data->put('type', 'edit');
        $validator = m::mock();
        $validator->shouldReceive('fails')->once()->andReturn(false);
        Validator::shouldReceive('make')
            ->with($data->except('comment')->toArray(), TicketUpdateRequest::$rules)
            ->andReturn($validator);
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->createEditAction($data);
        $this->assertNull($return);
    }

    public function testCreateEditActionWithUserNotValid()
    {
        $data = $this->createData('ticket_id', 'auth_id', 'body', 'priority', '_comment');
        $data->put('type', 'edit');
        $errors = m::mock();
        $errors->shouldReceive('all')->once()->andReturn([]);
        $validator = m::mock();
        $validator->shouldReceive('fails')->once()->andReturn(true);
        $validator->shouldReceive('errors')->once()->andReturn($errors);
        Validator::shouldReceive('make')
            ->with($data->except('comment')->toArray(), TicketUpdateRequest::$rules)
            ->andReturn($validator);
        Log::shouldReceive('debug')->once();
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->createEditAction($data);
        $this->assertFalse($return);
    }

    public function testGetDeptHasDept()
    {
        $data = $this->createData('dept');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->getDept($data);
        $this->assertEquals($data->get('dept'), $return);
    }

    public function testGetDeptNoDept()
    {
        $data = $this->createData();
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->getDept($data);
        $this->assertEquals(config('system.defaultdept'), $return);
    }

    public function testCreateTickeHasTicket()
    {
        $data = $this->createData('ticket_id');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->createTicket($data);
        $this->assertEquals($data, $return);
    }

    public function testCreateTicket()
    {
        $data = $this->createData();
        $fetch = m::mock('App\Jobs\FetchEmailJob[dispatchTicket]', [$this->email]);
        $ticket = factory(App\Ticket::class)->make();
        $fetch->shouldReceive('dispatchTicket')->once()->with($data)->andReturn($ticket);
        $return = $fetch->createTicket($data);
        $data = $data->merge(['ticket' => $ticket, 'ticket_id' => $ticket->id]);
        $this->assertEquals($data, $return);
    }

    public function testCreateTicketDispatchFails()
    {
        $data = $this->createData();
        $fetch = m::mock('App\Jobs\FetchEmailJob[dispatchTicket]', [$this->email]);
        $fetch->shouldReceive('dispatchTicket')->once()->with($data)->andReturn(null);
        $return = $fetch->createTicket($data);
        $this->assertEquals(null, $return);
    }

    public function testdispatchTicket()
    {
        $this->expectsJobs(App\Jobs\TicketCreateJob::class);
        $data = $this->createData('auth_id', 'user_id', 'title', 'body');
        $fetch = m::mock('App\Jobs\FetchEmailJob[buildTicket]', [$this->email]);
        $fetch->shouldReceive('buildTicket')->once()->andReturn($data);
        $check = m::mock();
        $check->shouldReceive('fails')->once()->andReturn(false);
        Validator::shouldReceive('make')->once()->with($data->toArray(), m::any())->andReturn($check);
        Log::shouldReceive('debug')->once();
        $return = $fetch->dispatchTicket($data);
        $this->assertNull($return);
    }

    public function testdispatchTicketValidateFails()
    {
        $data = $this->createData('auth_id', 'user_id', 'title', 'body');
        $fetch = m::mock('App\Jobs\FetchEmailJob[buildTicket]', [$this->email]);
        $fetch->shouldReceive('buildTicket')->once()->andReturn($data);
        $errors = m::mock();
        $errors->shouldReceive('all')->once()->andReturn([]);
        $check = m::mock();
        $check->shouldReceive('fails')->once()->andReturn(true);
        $check->shouldReceive('errors')->once()->andReturn($errors);
        Validator::shouldReceive('make')->once()->with($data->toArray(), m::any())->andReturn($check);
        Log::shouldReceive('debug')->once();
        $return = $fetch->dispatchTicket($data);
        $this->assertFalse($return);
    }

    public function testGetAssignnedNoClaimedNoAssigned()
    {
        $data = $this->createData();
        $user_interface = m::mock('App\Contracts\Repositories\UserInterface');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->getAssigned($data, $user_interface);
        $this->assertEquals($data, $return);
    }

    public function testGetAssignedWithClaimNoAssigned()
    {
        $user_interface = m::mock('App\Contracts\Repositories\UserInterface');
        $data = $this->createData('claim', 'auth_id');
        $fetch = new FetchEmailJob($this->email);
        $return = $fetch->getAssigned($data, $user_interface);
        $this->assertEquals($data->put('assigned_id', $data->get('auth_id')), $return);
    }

    public function testGetAssignedNoClaimWithAssigned()
    {
        $data = $this->createData('assigned');
        // $fetch = m::mock('App\Jobs\FetchEmailJob', [$this->email]);
        $first = m::mock();
        $first->id = 444;
        $user = m::mock();
        $user->shouldReceive('first')
            ->once()
            ->andReturn($first);
        $user_interface = m::mock('App\Contracts\Repositories\UserInterface');
        $user_interface->shouldReceive('findWhere')
            ->once()
            ->with(['username' => $data->get('assigned'), 'is_staff' => true], ['id'])
            ->andReturn($user);
        $fetch = new FetchEmailJob($this->email);
        $fetch->getAssigned($data, $user_interface);

    }

    public function testBuildTicket()
    {
        $data = $this->createData('user_id', 'auth_id', 'body', 'comment');
        $fetch = m::mock('App\Jobs\FetchEmailJob[getDept,getAssigned]', [$this->email]);
        $fetch->shouldReceive('getDept')->once()->andReturn(44);
        $data = $data->merge(['dept_id' => 44, 'priority' => 3, 'assigned_id' => 55])
            ->forget('comment');
        $return = $fetch->buildTicket($data);
        $this->assertEquals($data, $return);
    }

    public function testProcessEmail()
    {
        $this->email->mail_delete = true;
        $this->email->mail_archivefolder = 'Archive';
        $message = m::mock('Fetch\Message');
        $message->shouldReceive('delete')->once();
        $message->shouldReceive('moveToMailBox')->with($this->email->mail_archivefolder)->once();
        $fetch = new FetchEmailJob($this->email);
        $fetch->processEmail($message);
    }

    protected function createData()
    {
        $faker = Faker::create();
        $data = collect([
            'auth_id' => $faker->numberBetween(1, 1000),
            'user_id' => $faker->numberBetween(1, 1000),
            'name' => $faker->name,
            'email' => $faker->email,
            'reply' => $faker->sentence,
            'comment' => $faker->sentence,
            'closed' => $faker->sentence,
            'open' => $faker->sentence,
            'resolved' => $faker->sentence,
            'body' => $faker->paragraph,
            'source' => 'mail',
            'defer_event' => true,
            'ticket_id' => $faker->numberBetween(1, 1000),
            'hours' => $faker->randomFloat(2, 0, 10),
            'priority' => $faker->numberBetween(1, 5),
            'dept' => $faker->randomDigitNotNull,
            'title' =>$faker->sentence,
            'claim' => '',
            'assigned' => $faker->username
        ]);

        $default = ['source', 'defer_event'];
        $empty = [];
        foreach (func_get_args() as $value) {
            if (strpos($value, '_') === 0) {
                $key = substr($value, 1);
                $data->put($key, '');
                $empty[] = $key;
            }
        }

        return $data->only(array_merge($default, $empty, func_get_args()));
    }
}
