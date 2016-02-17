<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Repositories\Criteria\Tickets\WithAll;
use App\Repositories\Criteria\Users\WhereNotify;
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

        $this->ticket->pushCriteria(new WithAll())->find($event->ticket->id);
        $ticket = $this->ticket->all()->first();
        $ticket->events = $ticket->actions;
        $this->user->pushCriteria(new WhereNotify($ticket));

        $email = $this->email->find(config('settings.mail.default'));

        foreach ($this->user->all() as $user) {
            $this->mailer->queue(
                ['text' => 'mail.ticket_action'],
                ['user' => $user->toArray(), 'ticket' => $ticket->toArray()],
                function ($message) use ($user, $ticket, $email) {
                    $message->from($email->email, $email->name)
                        ->to($user->email, $user->display_name)
                        ->subject(trans('mail.subject.new', ['id' => $ticket->id, 'title' => str_limit($ticket->title, 40)], $user->locale));
                }
            );
        }
    }
}
