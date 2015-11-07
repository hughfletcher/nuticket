<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Mockery as m;
use App\Listeners\NotifyTicketCreatedListener;

class NotifyTicketCreatedListenerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHandle()
    {
        config(['mail.pretend' => true]);

        $staff = factory(App\User::class, 3)->make();
        $ticket = factory(App\Ticket::class)->make();
        $event = m::mock('App\Events\TicketCreatedEvent');
        $event->ticket = $ticket;
        $user  = m::mock('App\Contracts\Repositories\UserInterface');
        $user->shouldReceive('findWhere')
            ->once()
            ->with(['username' => 'hfletcher'])
            ->andReturn($staff);

        Mail::shouldReceive('send')
        ->with(
            ['text' => 'mail.new_ticket'],
            nonEmptyArray(),
            m::on(function ($closure) use ($staff, $ticket) {
                $message = m::mock('Illuminate\Mail\Message');
                $message->shouldReceive('from')->once()->with('support@tennesseetractor.com', 'IT Support')->andReturn($message);
                $message->shouldReceive('to')->once()->andReturn($message);
                $message->shouldReceive('subject')->once();
                $closure($message);
                return true;
            })
        )->times(3);

        $notify = new NotifyTicketCreatedListener($user, $this->app['mailer']);
        $notify->handle($event);

    }
}
