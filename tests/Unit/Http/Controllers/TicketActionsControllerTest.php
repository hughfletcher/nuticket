<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Http\Controllers\TicketActionsController;
use Mockery as m;
use App\Http\Requests\ActionCreateRequest;
use Faker\Factory as Faker;

class TicketActionsControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->user_model = factory(App\User::class)->make(['id' => $this->faker->randomNumber]);
        $this->be($this->user_model);
        $this->ticket = m::mock('App\Contracts\Repositories\TicketInterface');
        
        
        // $this->user = m::mock('App\Contracts\Repositories\UserInterface');
    }

    public function testStoreWithReplyTypeOpenStatusOnOpenTicket()
    {
        $ticket = factory(App\Ticket::class)->make(['status' => 'open']);
        $this->ticket->shouldReceive('find')->andReturn($ticket);
        
        $request = new ActionCreateRequest([
            'type' => 'reply',
            'status' => 'open',
            'time_at' => $this->faker->date(config('settings.format.date'))
        ]);

        $this->expectToDispatcher();

        $controller = new TicketActionsController($this->ticket);        
        $controller->store($request);
    }

    public function testStoreWithReplyTypeClosedStatusOnOpenTicket()
    {
        $ticket = factory(App\Ticket::class)->make(['status' => 'open']);
        $this->ticket->shouldReceive('find')->andReturn($ticket);
        
        $request = new ActionCreateRequest([
            'type' => 'reply',
            'status' => 'closed',
            'time_at' => $this->faker->date(config('settings.format.date'))
        ]);

        $this->expectToDispatcher('closed');

        $controller = new TicketActionsController($this->ticket);        
        $controller->store($request);
    }

    public function testStoreWithReplyTypeResolvedStatusOnOpenTicket()
    {
        $ticket = factory(App\Ticket::class)->make(['status' => 'open']);
        $this->ticket->shouldReceive('find')->andReturn($ticket);
        
        $request = new ActionCreateRequest([
            'type' => 'reply',
            'status' => 'resolved',
            'time_at' => $this->faker->date(config('settings.format.date'))
        ]);

        $this->expectToDispatcher('resolved');

        $controller = new TicketActionsController($this->ticket);        
        $controller->store($request);
    }

    public function testStoreWithReplyTypeOpenStatusOnClosedTicket()
    {
        $ticket = factory(App\Ticket::class)->make(['status' => 'closed']);
        $this->ticket->shouldReceive('find')->andReturn($ticket);
        
        $request = new ActionCreateRequest([
            'type' => 'reply',
            'status' => 'open',
            'time_at' => $this->faker->date(config('settings.format.date'))
        ]);

        $this->expectToDispatcher('open');

        $controller = new TicketActionsController($this->ticket);        
        $controller->store($request);
    }

    public function testStoreWithReplyTypeNullStatusOnClosedTicket()
    {
        $ticket = factory(App\Ticket::class)->make(['status' => 'closed']);
        $this->ticket->shouldReceive('find')->andReturn($ticket);
        
        $request = new ActionCreateRequest([
            'type' => 'reply',
            'status' => null,
            'time_at' => $this->faker->date(config('settings.format.date'))
        ]);

        $this->expectToDispatcher('reply');

        $controller = new TicketActionsController($this->ticket);        
        $controller->store($request);
    }

    public function expectToDispatcher($expect_type = 'reply')
    {
        $action = factory(App\TicketAction::class)->make(['id' => $this->faker->randomDigit]);
        $dispatcher = m::mock('Illuminate\Bus\Dispatcher[dispatchFrom]', [$this->app]);
        $dispatcher->shouldReceive('dispatchFrom')->once()
            ->with('App\Jobs\ActionCreateJob', m::on(function($param) use ($expect_type) {
                // var_dump($param->all())
                return $param->get('type') == $expect_type;
            }), [])
            ->andReturn($action);

        $this->app->instance('Illuminate\Contracts\Bus\Dispatcher', $dispatcher);
    }

}
