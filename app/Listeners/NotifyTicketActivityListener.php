<?php

namespace App\Listeners;

use App\Events\ActionCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\EmailInterface;
use Illuminate\Mail\Mailer;
use App\Repositories\Criteria\Tickets\WithAll;
use App\Repositories\Criteria\Users\WhereNotify;

class NotifyTicketActivityListener implements ShouldQueue
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
        $this->email = $email;
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  ActionCreatedEvent  $event
     * @return void
     */
    public function handle(ActionCreatedEvent $event)
    {
        // $staff = $this->user->find(explode(',', config('settings.mail.notify')));

        $this->ticket->pushCriteria(new WithAll)->find($event->action->ticket_id);
        $ticket = $this->ticket->all()->first();
        $ticket->events = $ticket->actions->where('id', $event->action->id);

        $this->user->pushCriteria(new WhereNotify($ticket));

        $email = $this->email->find(config('settings.mail.default'));

        foreach ($this->user->all() as $user) {
            $this->mailer->queue(
                ['text' => 'mail.ticket_action'],
                ['user' => $user->toArray(), 'ticket' => $ticket->toArray()],
                function ($message) use ($user, $ticket, $email) {
                    $message->from($email->email, $email->name)
                        ->to($user['email'], $user['display_name'])
                        ->subject(trans('mail.subject.activity', ['id' => $ticket['id'], 'title' => str_limit($ticket['title'], 40)], $user['locale']));
                }
            );
        }


    }
}
