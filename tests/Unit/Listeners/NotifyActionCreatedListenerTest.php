<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Mockery as m;
use App\Listeners\NotifyActionCreatedListener;

class NotifyActionCreatedListenerTest extends TestCase
{
    public function testHandle()
    {
        config(['mail.pretend' => true]);

        $staff = factory(App\User::class, 3)->make();
        $action = factory(App\TicketAction::class)->make();
        $event = m::mock('App\Events\ActionCreatedEvent');
        $event->action = $action;
        $user  = m::mock('App\Contracts\Repositories\UserInterface');
        $user->shouldReceive('findWhere')
            ->once()
            ->with(['username' => 'hfletcher'])
            ->andReturn($staff);


        Mail::shouldReceive('send')
        ->with(
            ['text' => 'mail.new_action'],
            nonEmptyArray(),
            m::on(function ($closure) use ($staff) {
                $message = m::mock('Illuminate\Mail\Message');
                $message->shouldReceive('from')->once()->with('support@tennesseetractor.com', 'IT Support')->andReturn($message);
                $message->shouldReceive('to')->once()->andReturn($message);
                $message->shouldReceive('subject')->once();
                $closure($message);
                return true;
            })
        )->times(3);

        $notify = new NotifyActionCreatedListener($user, $this->app['mailer']);
        $notify->handle($event);

    }
}
