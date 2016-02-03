<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Repositories\Criteria\Tickets\WithLoadedActions;
use App\Repositories\Criteria\Tickets\WithAssigned;
use App\Repositories\Criteria\WithDept;
use App\Repositories\Criteria\WithOrg;
use App\Repositories\Criteria\WithUser;
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
        $staff = $this->user->find(explode(',', config('settings.mail.notify')));

        $ticket = $this->ticket->pushCriteria(new WithLoadedActions())
            ->pushCriteria(new WithDept())
            ->pushCriteria(new WithOrg())
            ->pushCriteria(new WithUser())
            ->pushCriteria(new WithAssigned())
            ->find($event->ticket->id)
            ->toArray();
        $email = $this->email->find(config('settings.mail.default'));

        foreach ($staff as $user) {
            $this->mailer->queue(
                ['text' => 'mail.ticket_action'],
                ['user' => $user, 'ticket' => $ticket],
                function ($message) use ($user, $ticket, $email) {
                    $message->from($email->email, $email->name)
                        ->to($user->email, $user->display_name)
                        ->subject(trans('mail.subject.new', ['id' => $ticket['id'], 'title' => str_limit($ticket['title'], 40)], $user['locale']));
                }
            );
        }
    }
}
