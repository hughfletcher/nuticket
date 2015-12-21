<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\ActionCreateJob;
use Tests\TestCase;
use Mockery as m;
use App\TicketAction;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ActionCreateJobTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->action = m::mock('App\Contracts\Repositories\TicketActionInterface');
        $this->ticket = m::mock('App\Contracts\Repositories\TicketInterface');
        $this->time = m::mock('App\Contracts\Repositories\TimeLogInterface');
        $this->faker = Faker::create();
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHandleBaseParams()
    {
        $this->expectsEvents(App\Events\ActionCreatedEvent::class);
        $attrs = collect([123, 456, 'reply', $this->faker->sentence]);

        $job = m::mock('App\Jobs\ActionCreateJob[updateTicket]', $attrs->values()->toArray());
        $job->shouldReceive('updateTicket')->once()->with($this->ticket);

        $action = 'test action';
        $this->action->shouldReceive('create')
            ->with(m::on(function ($arg) {
                $this->assertArrayHasKey('ticket_id', $arg);
                $this->assertArrayHasKey('user_id', $arg);
                $this->assertArrayHasKey('type', $arg);
                $this->assertArrayHasKey('body', $arg);
                return true;
            }))
            ->once()
            ->andReturn($action);

        $return = $job->handle($this->action, $this->time, $this->ticket);
        $this->assertEquals('test action', $return);

    }

    public function testHandleTransfer()
    {
        $this->expectsEvents(App\Events\ActionCreatedEvent::class);
        $attrs = collect([123, 456, 'transfer', $this->faker->sentence, 789, null, 4]);

        $job = m::mock('App\Jobs\ActionCreateJob[updateTicket]', $attrs->values()->toArray());
        $job->shouldReceive('updateTicket')->once()->with($this->ticket);

        $action = 'test reply action';
        $this->action->shouldReceive('create')
            ->with(m::on(function ($arg) {
                $this->assertArrayHasKey('ticket_id', $arg);
                $this->assertArrayHasKey('user_id', $arg);
                $this->assertArrayHasKey('type', $arg);
                $this->assertArrayHasKey('body', $arg);
                $this->assertArrayHasKey('transfer_id', $arg);
                return true;
            }))
            ->once()
            ->andReturn($action);

        $return = $job->handle($this->action, $this->time, $this->ticket);
        $this->assertEquals('test reply action', $return);

    }

    public function testHandleAssign()
    {
        $this->expectsEvents(App\Events\ActionCreatedEvent::class);
        $attrs = collect([123, 456, 'transfer', $this->faker->sentence, null, 789]);

        $job = m::mock('App\Jobs\ActionCreateJob[updateTicket]', $attrs->values()->toArray());
        $job->shouldReceive('updateTicket')->once()->with($this->ticket);

        $action = 'test reply action';
        $this->action->shouldReceive('create')
            ->with(m::on(function ($arg) {
                $this->assertArrayHasKey('ticket_id', $arg);
                $this->assertArrayHasKey('user_id', $arg);
                $this->assertArrayHasKey('type', $arg);
                $this->assertArrayHasKey('body', $arg);
                $this->assertArrayHasKey('assigned_id', $arg);
                return true;
            }))
            ->once()
            ->andReturn($action);

        $return = $job->handle($this->action, $this->time, $this->ticket);
        $this->assertEquals('test reply action', $return);

    }

    public function testHandleCommentWithHoursNoEvent()
    {
        $attrs = collect([123, 456, 'comment', $this->faker->sentence, null, null, 3.54, null, null, true]);
        $action = factory(App\TicketAction::class)->make();

        $this->action->shouldReceive('create')
            ->with(m::on(function ($arg) {
                $this->assertArrayHasKey('ticket_id', $arg);
                $this->assertArrayHasKey('user_id', $arg);
                $this->assertArrayHasKey('type', $arg);
                $this->assertArrayHasKey('body', $arg);
                return true;
            }))
            ->once()
            ->andReturn($action);

        $job = m::mock('App\Jobs\ActionCreateJob[updateTicket,updateTimeLog]', $attrs->values()->toArray());
        $job->shouldReceive('updateTimeLog')->once()->with($action, $this->time);
        $job->shouldReceive('updateTicket')->once()->with($this->ticket);
        // $job->shouldReceive('updateTimeLog')->once()->with($this->ticket)

        $return = $job->handle($this->action, $this->time, $this->ticket);
        $this->assertEquals($action, $return);

    }

    public function testUpdateTimeLog()
    {
        $faker = Faker::create();
        $action = factory(App\TicketAction::class)->make();
        $actions = 3.45;
        $this->time->shouldReceive('create')
            ->with([
                'user_id' => $action->user_id,
                'hours' => 3.54,
                'type' => 'action',
                'ticket_action_id' => $action->id,
                'time_at' => Carbon::createFromDate(2012, 1, 1)
            ])
            ->once();
        $job = new ActionCreateJob(123, 456, 'comment', $faker->sentence, null, null, 3.54, Carbon::createFromDate(2012, 1, 1));
        $job->updateTimeLog($action, $this->time);

    }

    public function testUpdateTicket()
    {
        $faker = Faker::create();
        $attrs = [123, 456, 'comment', $faker->sentence, null, null, 3.54];
        $job = m::mock('App\Jobs\ActionCreateJob[updateStatus,updateHours,updateDept,updateAssigned]', $attrs);
        $job->shouldReceive('updateStatus')->once()->andReturn(['status' => 'open']);
        $job->shouldReceive('updateHours')->once()->andReturn(['hours' => 4]);
        $job->shouldReceive('updateDept')->once()->andReturn(['dept_id' => 234]);
        $job->shouldReceive('updateAssigned')->once()->andReturn(['assigned_id' => 678]);
        $this->ticket->shouldReceive('update')->once()
            ->with(m::subset(['status' => 'open', 'hours' => 4, 'dept_id' => 234, 'assigned_id' => 678]) AND m::hasKey('last_action_at'), 123);
        $ticket = m::mock();
        $ticket->hours = 3.25;
        $this->ticket->shouldReceive('find')->with(123)->once()->andReturn($ticket);
        $job->updateTicket($this->ticket);
    }

    public function testUpdateAssignnedNotAssigned()
    {
        $job = new ActionCreateJob(123, 456, 'comment', $this->faker->sentence);
        $return = $job->updateAssigned();
        $this->assertEmpty($return);
    }

    public function testUpdateAssignned()
    {
        $job = new ActionCreateJob(123, 456, 'assign', $this->faker->sentence, null, 789);
        $return = $job->updateAssigned();
        $this->assertEquals(['assigned_id' => 789], $return);
    }

    public function testUpdateDeptNoTransfer()
    {
        $job = new ActionCreateJob(123, 456, 'comment', $this->faker->sentence);
        $return = $job->updateDept();
        $this->assertEmpty($return);
    }

    public function testUpdateDept()
    {
        $job = new ActionCreateJob(123, 456, 'transfer', $this->faker->sentence, 789);
        $return = $job->updateDept();
        $this->assertEquals(['dept_id' => 789], $return);
    }

    public function testUpdateStatusNoChange()
    {
        $job = new ActionCreateJob(123, 456, 'transfer', $this->faker->sentence, 789);
        $return = $job->updateStatus();
        $this->assertEmpty($return);
    }

    public function testUpdateStatusToOpen()
    {
        $job = new ActionCreateJob(123, 456, 'open', $this->faker->sentence, 789);
        $return = $job->updateStatus();
        $this->assertEquals(['status' => 'open', 'closed_at' => null], $return);
    }

    public function testUpdateStatusOnReply()
    {
        $job = new ActionCreateJob(123, 456, 'reply', $this->faker->sentence, null, null, 1.5);
        $return = $job->updateStatus();
        $this->assertEmpty($return);
    }

    public function testUpdateStatusToClosed()
    {
        $job = new ActionCreateJob(123, 456, 'closed', $this->faker->sentence);
        $return = $job->updateStatus();
        $this->assertArraySubset(['status' => 'closed'], $return);
        $this->assertArrayHasKey('closed_at', $return);
    }

    public function testUpdateStatusToResolved()
    {
        $job = new ActionCreateJob(123, 456, 'resolved', $this->faker->sentence);
        $return = $job->updateStatus();
        $this->assertArraySubset(['status' => 'resolved'], $return);
        $this->assertArrayHasKey('closed_at', $return);
    }

    public function testUpdateHoursNoHours()
    {
        $job = new ActionCreateJob(123, 456, 'edit', $this->faker->sentence);
        $this->assertEquals([], $job->updateHours(0));
    }

    public function testUpdateHoursWithHours()
    {
        $job = new ActionCreateJob(123, 456, 'open', $this->faker->sentence, null, null, 3.25);
        $this->assertEquals(['hours' => 5.25], $job->updateHours(2));
    }
}
