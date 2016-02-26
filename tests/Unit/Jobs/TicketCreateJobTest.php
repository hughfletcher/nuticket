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
        $ticket = factory(App\Ticket::class)->make(['id' => 8765]); 
        $attrs = ['auth_id' => 123, 
            'user_id' => $ticket->user_id, 
            'title' => $this->faker->sentence, 
            'body' => $this->faker->paragraph, 
            'source' => 'mail', 
            'status' => $ticket->status, 
            'dept_id' => $ticket->dept_id, 
            'org_id' => $ticket->org_id, 
            'assigned_id' => $ticket->assigned_id,
        ];
        
        $this->ticket->shouldReceive('job_create')->once()->with(m::on(function($param) use($attrs) {
            return empty(array_diff($param, array_only($attrs, ['user_id', 'assigned_id', 'priority', 'dept_id', 'org_id', 'status'])));
        }))->andReturn($ticket);
        $this->action->shouldReceive('create')->once()->with(m::on(function($param) use ($attrs, $ticket) {
            $attrs = array_merge($attrs, ['type' => 'create', 'user_id' => $attrs['auth_id'], 'ticket_id' => $ticket->id]);
            return empty(array_diff($param, array_only($attrs, ['ticket_id', 'type', 'title', 'body', 'source', 'user_id'])));
        }));

        $class = new ReflectionClass('App\Jobs\TicketCreateJob');
        $job = $class->newInstanceArgs($attrs);

        $this->assertEquals($ticket, $job->handle($this->ticket, $this->action));
    }

    public function testHandleDeferEvent()
    {
        $this->doesntExpectEvents(App\Events\TicketCreatedEvent::class);
        $ticket = factory(App\Ticket::class)->make(['id' => 8765]); 
        $attrs = ['auth_id' => 123, 
            'user_id' => $ticket->user_id, 
            'title' => $this->faker->sentence, 
            'body' => $this->faker->paragraph, 
            'source' => 'mail', 
            'status' => $ticket->status, 
            'dept_id' => $ticket->dept_id, 
            'org_id' => $ticket->org_id, 
            'assigned_id' => $ticket->assigned_id,
            'defer_event' => true
        ];
        
        $this->ticket->shouldReceive('job_create')->once()->with(m::on(function($param) use($attrs) {
            return empty(array_diff($param, array_only($attrs, ['user_id', 'assigned_id', 'priority', 'dept_id', 'org_id', 'status'])));
        }))->andReturn($ticket);
        $this->action->shouldReceive('create')->once()->with(m::on(function($param) use ($attrs, $ticket) {
            $attrs = array_merge($attrs, ['type' => 'create', 'user_id' => $attrs['auth_id'], 'ticket_id' => $ticket->id]);
            return empty(array_diff($param, array_only($attrs, ['ticket_id', 'type', 'title', 'body', 'source', 'user_id'])));
        }));

        $class = new ReflectionClass('App\Jobs\TicketCreateJob');
        $job = $class->newInstanceArgs($attrs);

        $this->assertEquals($ticket, $job->handle($this->ticket, $this->action));
    }

}
