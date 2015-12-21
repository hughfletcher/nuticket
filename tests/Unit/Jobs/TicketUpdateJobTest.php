<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\TicketUpdateJob;
use Tests\TestCase;
use Mockery as m;
use Faker\Factory as Faker;

class TicketUpdateJobTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->ticket = m::mock('App\Contracts\Repositories\TicketInterface');
        $this->action = m::mock('App\Contracts\Repositories\TicketActionInterface');
        $this->faker = Faker::create();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHandle()
    {
        $this->expectsEvents(App\Events\ActionCreatedEvent::class);
        $action_old = factory(App\TicketAction::class, 2)->make();
        $action_updated = factory(App\TicketAction::class)->make();
        $action_edit = factory(App\TicketAction::class)->make();
        $ticket_old = factory(App\Ticket::class)->make();
        $ticket_updated = factory(App\Ticket::class)->make();
        $this->ticket->shouldReceive('find')->twice()->andReturn($ticket_old, $ticket_updated);
        $this->ticket->shouldReceive('update')->once()->with(['user_id' => 789], 123);
        $this->action->shouldReceive('findWhere')->once()->with(['ticket_id' => 123, 'type' => 'create'])->andReturn($action_old);
        $this->action->shouldReceive('update')->once()->with([], $action_old->first()->id);
        $this->action->shouldReceive('find')->once()->with($action_old->first()->id)->andReturn($action_updated);

        $attrs = [123, 456, 789];
        $job = m::mock('App\Jobs\TicketUpdateJob[getActionChanges,getTicketChanges,createEditAction]', $attrs);
        $job->shouldReceive('getActionChanges')->once()->with($action_old->first(), $action_updated)->andReturn([]);
        $job->shouldReceive('getTicketChanges')->once()->with($ticket_old, $ticket_updated)->andReturn([]);
        $job->shouldReceive('createEditAction')->with($this->action, [])->once()->andReturn($action_edit);
        $return = $job->handle($this->ticket, $this->action);
        $this->assertEquals($action_edit->id, $return->id);
        $this->assertEquals($action_edit->ticket_id, $return->ticket_id);
        $this->assertEquals($ticket_old, $return->ticketOld);
        $this->assertEquals($action_old->first(), $return->actionOld);
        $this->assertEquals($ticket_updated, $return->ticketUpdated);
    }

    public function testHandleDeferEvent()
    {
        $action_old = factory(App\TicketAction::class, 2)->make();
        $action_updated = factory(App\TicketAction::class)->make();
        $action_edit = factory(App\TicketAction::class)->make();
        $ticket_old = factory(App\Ticket::class)->make();
        $ticket_updated = factory(App\Ticket::class)->make();
        $this->ticket->shouldReceive('find')->twice()->andReturn($ticket_old, $ticket_updated);
        $this->ticket->shouldReceive('update')->once()->with(['user_id' => 789], 123);
        $this->action->shouldReceive('findWhere')->once()->with(['ticket_id' => 123, 'type' => 'create'])->andReturn($action_old);
        $this->action->shouldReceive('update')->once()->with([], $action_old->first()->id);
        $this->action->shouldReceive('find')->once()->with($action_old->first()->id)->andReturn($action_updated);

        $attrs = [123, 456, 789, null, null, null, $this->faker->sentence, null, true];
        $job = m::mock('App\Jobs\TicketUpdateJob[getActionChanges,getTicketChanges,createEditAction]', $attrs);
        $job->shouldReceive('getActionChanges')->once()->with($action_old->first(), $action_updated)->andReturn([]);
        $job->shouldReceive('getTicketChanges')->once()->with($ticket_old, $ticket_updated)->andReturn([]);
        $job->shouldReceive('createEditAction')->with($this->action, [])->once()->andReturn($action_edit);
        $return = $job->handle($this->ticket, $this->action);
        $this->assertEquals($action_edit->id, $return->id);
        $this->assertEquals($action_edit->ticket_id, $return->ticket_id);
        $this->assertEquals($ticket_old, $return->ticketOld);
        $this->assertEquals($action_old->first(), $return->actionOld);
        $this->assertEquals($ticket_updated, $return->ticketUpdated);
    }

    public function testCreateEditAction()
    {
        $attrs = [123, 456, 789, null, null, null, $this->faker->sentence, 'mail'];
        $job = m::mock('App\Jobs\TicketUpdateJob[createBody]', $attrs);
        $job->shouldReceive('createBody')->once()->with(['change'])->andReturn('body changes');
        $this->action
            ->shouldReceive('create')
            ->once()
            ->with([
                'type' => 'edit',
                'body' => "body changes\n" . $attrs[6],
                'user_id' => 456,
                'ticket_id' => 123,
                'source' => 'mail'
            ]);
        $job->createEditAction($this->action, ['change']);
    }

    public function testGetTicketChanges()
    {
        $old = factory(App\Ticket::class)->make([
            'user' => factory(App\User::class)->make(),
            'priority' => 2
        ]);
        $updated = factory(App\Ticket::class)->make([
            'user' => factory(App\User::class)->make(),
            'priority' =>4
        ]);
        $changed = [
            'User' => ['from' => $old->user->display_name, 'to' => $updated->user->display_name],
            'Priority' => ['from' => $old->priority, 'to' => $updated->priority]
        ];
        $job = new TicketUpdateJob(123,345,678);
        $this->assertEquals($changed, $job->getTicketChanges($old, $updated));
    }

    public function testGetActionChanges()
    {
        $old = factory(App\TicketAction::class)->make();
        $action = factory(App\TicketAction::class)->make();
        $changed = [
            'Title' => ['from' => $action->title, 'to' => $old->title],
            'Body' => ['from' => $action->body, 'to' => $old->body]
        ];
        $job = new TicketUpdateJob(123, 345, 678);
        $this->assertEquals($changed, $job->getActionChanges($old, $action));
    }

    function testCreateBody()
    {
        $changes = [
            'User' => ['from' => 'George Constanza', 'to' => 'Kramer'],
            'Title' => ['from' => 'Help I have problems', 'to' => 'Help I can\'t log in']
        ];
        $body = "User changed from George Constanza to Kramer.\n"
            ."Title changed from Help I have problems to Help I can't log in.\n";
        $job = new TicketUpdateJob(123, 345, 678);
        $this->assertEquals($body, $job->createBody($changes));
    }

    function testCreateBodyWithReason()
    {
        $changes = [
            'User' => ['from' => 'George Constanza', 'to' => 'Kramer'],
            'Title' => ['from' => 'Help I have problems', 'to' => 'Help I can\'t log in']
        ];
        $body = "User changed from George Constanza to Kramer.\n"
            ."Title changed from Help I have problems to Help I can't log in.\n"
            ."\nWrong Person.";
        $job = new TicketUpdateJob(123, 345, 678, null, null, null, 'Wrong Person.');
        $this->assertEquals($body, $job->createBody($changes));
    }
}
