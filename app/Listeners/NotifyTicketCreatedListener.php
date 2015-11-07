<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use Illuminate\Mail\Mailer;

class NotifyTicketCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserInterface $user, Mailer $mailer)
    {
            $this->user = $user;
            $this->mailer = $mailer;
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

        foreach ($staff as $user) {
            $this->mailer->send(
                ['text' => 'mail.new_ticket'],
                ['user' => $user, 'ticket' => $ticket],
                function ($message) use ($user, $ticket) {
                    $message->from('support@tennesseetractor.com', 'IT Support')
                        ->to($user->email, $user->display_name)
                        ->subject('[#' . $ticket->id . '] ' . 'New Ticket Alert');
                }
            );
        }
    }
}
