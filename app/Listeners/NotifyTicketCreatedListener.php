<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Repositories\Criteria\Tickets\WithLoadedActions;
use App\Repositories\Criteria\WithDept;
use App\Contracts\Repositories\EmailInterface;
use Illuminate\Mail\Mailer;

class NotifyTicketCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserInterface $user, TicketInterface $ticket, EmailInterface $email, Mailer $mailer)
    {
            $this->user = $user;
            $this->ticket = $ticket;
            $this->mailer = $mailer;
            $this->email = $email;
    }

    /**
     * Handle the event.
     *
     * @param  TicketCreatedEvent  $event
     * @return void
     */
    public function handle(TicketCreatedEvent $event)
    {
        $staff = $this->user->find(explode(',', config('mail.notify')));

        $ticket = $this->ticket->pushCriteria(new WithLoadedActions())
            ->pushCriteria(new WithDept())
            ->find($event->ticket->id);
        $email = $this->email->find(config('mail.default'));

        foreach ($staff as $user) {
            $this->mailer->queue(
                ['text' => 'mail.ticket_action'],
                ['user' => $user, 'ticket' => $ticket],
                function ($message) use ($user, $ticket, $email) {
                    $message->from($email->email, $email->name)
                        ->to($user->email, $user->display_name)
                        ->subject('[New - #' . $ticket->id . '] ' . str_limit($ticket->title, 40));
                }
            );
        }
    }
}
