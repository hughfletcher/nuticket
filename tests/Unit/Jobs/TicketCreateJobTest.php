<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\TicketCreateJob;
use Tests\TestCase;
use Mockery as m;
use Faker\Factory as Faker;

class TicketCreateJobTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->ticket = m::mock('App\Contracts\Repositories\TicketInterface');
        $this->action = m::mock('App\Contracts\Repositories\TicketActionInterface');
        $this->faker = Faker::create();
    }

    public function testHandle()
    {
        $this->expectsEvents(App\Events\TicketCreatedEvent::class);
        $attrs = [123, 456, $this->faker->sentence, $this->faker->paragraph, 'mail', 'new', 7, 89, 2];
        $ticket = factory(App\Ticket::class)->make();
        $job = m::mock('App\Jobs\TicketCreateJob[createTicket,createAction]', $attrs);
        $job->shouldReceive('createTicket')
            ->with(m::type('Illuminate\Support\Collection'), $this->ticket)
            ->once()
            ->andReturn($ticket);
        $job->shouldReceive('createAction')
            ->with(m::type('Illuminate\Support\Collection'), $this->action)
            ->once();
        $this->assertEquals($ticket, $job->handle($this->ticket, $this->action));
    }

    public function testHandleDeferEvent()
    {
        $attrs = [123, 456, $this->faker->sentence, $this->faker->paragraph, 'mail', 'new', null, 89, 2, true];
        $ticket = factory(App\Ticket::class)->make();
        $job = m::mock('App\Jobs\TicketCreateJob[createTicket,createAction]', $attrs);
        $job->shouldReceive('createTicket')
            ->with(m::type('Illuminate\Support\Collection'), $this->ticket)
            ->once()
            ->andReturn($ticket);
        $job->shouldReceive('createAction')
            ->with(m::type('Illuminate\Support\Collection'), $this->action)
            ->once();
        $this->assertEquals($ticket, $job->handle($this->ticket, $this->action));
    }

    public function testCreateTicket()
    {
        $ticket = factory(App\Ticket::class)->make();
        $this->app['config']->set('system.defaultdept', 789);
        $job = new TicketCreateJob(123, 456, $this->faker->sentence, $this->faker->sentence);
        // $data = collect($ticket->only(['user_id', 'dept_id']));

        // $data = $ticket->only([])
        $this->ticket->shouldReceive('job_create')
            ->with(collect($ticket->toArray())->only(['user_id', 'dept_id'])->toArray())
            ->once()
            ->andReturn($ticket);

        $return = $job->createTicket(collect($ticket)->only(['user_id', 'dept_id']), $this->ticket);
        $this->assertEquals($ticket, $return);
    }

    public function testCreateAction()
    {
        $action = factory(App\TicketAction::class)->make([
            'auth_id' => 123
        ]);
        $this->app['config']->set('system.defaultdept', 789);
        $job = new TicketCreateJob(123, 456, $this->faker->sentence, $this->faker->sentence);
        // $data = collect($ticket->only(['user_id', 'dept_id']));

        // $data = $ticket->only([])
        $this->action->shouldReceive('create')
            ->with([
                'user_id' => $action->auth_id,
                'ticket_id' => $action->ticket_id,
                'type' => 'create',
                'title' => $action->title,
                'body' => $action->body,
                'source' => $action->source
            ])
            ->once()
            ->andReturn(1);

        $return = $job->createAction(collect($action), $this->action);
        // $this->assertEquals($ticket, $return);

    }

}
