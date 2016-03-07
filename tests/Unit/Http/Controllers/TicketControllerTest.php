<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Http\Controllers\TicketsController;
use Mockery as m;
use App\Http\Requests\TicketStoreRequest;
use Faker\Factory as Faker;

class TicketControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->user_model = factory(App\User::class)->make(['id' => $this->faker->randomNumber]);
        $this->be($this->user_model);
        $this->ticket = m::mock('App\Contracts\Repositories\TicketInterface');
        $this->user = m::mock('App\Contracts\Repositories\UserInterface');
    }

    public function testStoreWithReplyAndStatusClosed()
    {
        $this->ticket_model = factory(App\Ticket::class)->make(['id' => $this->faker->randomNumber]);

        $request = new TicketStoreRequest([
            'user_id' => 12,
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'reply_body' => $this->faker->sentence,
            'status' => 'closed'
        ]);

        $dispatch_request = $request->instance();
        $dispatch_request->merge(['auth_id' => auth()->user()->id]);

        $dispatcher = m::mock('Illuminate\Bus\Dispatcher[dispatchFrom]', [$this->app]);
        $dispatcher->shouldReceive('dispatchFrom')->once()
            ->with('App\Jobs\TicketCreateJob', $dispatch_request, [])
            ->andReturn($this->ticket_model);
        $dispatcher->shouldReceive('dispatchFrom')->once()
            ->with('App\Jobs\ActionCreateJob', m::on(function($param) {
                return empty(array_diff($param->toArray(), [
                    'ticket_id' => $this->ticket_model->id,
                    'user_id' => $this->user_model->id,
                    'hours' => null,
                    'time_at' => null,
                    'status' => 'closed',
                    'defer_event' => true
                ]));
            }), m::on(function($param) use ($request) {
                return empty(array_diff($param, ['type' => 'closed', 'body' => $request->get('reply_body')]));
            }));

        $this->app->instance('Illuminate\Contracts\Bus\Dispatcher', $dispatcher);
        
        $controller = new TicketsController($this->ticket, $this->user);        
        $controller->store($request);
    }

}
