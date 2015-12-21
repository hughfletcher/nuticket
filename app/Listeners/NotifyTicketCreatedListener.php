<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\EmailInterface;
use Illuminate\Mail\Mailer;

class NotifyTicketCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserInterface $user, EmailInterface $email, Mailer $mailer)
    {
            $this->user = $user;
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
        $staff = $this->user->findWhere(['username' => 'hfletcher']);
        $ticket = $event->ticket;
        $email = $this->email->find(config('mail.default'));

        foreach ($staff as $user) {
            $this->mailer->send(
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