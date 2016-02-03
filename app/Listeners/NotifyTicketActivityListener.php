<?php

namespace App\Listeners;

use App\Events\ActionCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\EmailInterface;
use Illuminate\Mail\Mailer;
use App\Repositories\Criteria\Tickets\WithLoadedActions;
use App\Repositories\Criteria\Tickets\WithAssigned;
use App\Repositories\Criteria\WithDept;
use App\Repositories\Criteria\WithOrg;
use App\Repositories\Criteria\WithUser;

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
        $staff = $this->user->find(explode(',', config('settings.mail.notify')));

        // dd($event->actions->lists('id')->toArray());

        $ticket = $this->ticket->pushCriteria(new WithLoadedActions($event->actions->lists('id')->toArray()))
            ->pushCriteria(new WithDept())
            ->pushCriteria(new WithOrg())
            ->pushCriteria(new WithUser())
            ->pushCriteria(new WithAssigned())
            ->find($event->actions->first()->ticket_id)
            ->toArray();

        $email = $this->email->find(config('settings.mail.default'));

        foreach ($staff as $user) {
            $this->mailer->queue(
                ['text' => 'mail.ticket_action'],
                ['user' => $user, 'ticket' => $ticket],
                function ($message) use ($user, $ticket, $email) {
                    $message->from($email->email, $email->name)
                        ->to($user->email, $user->display_name)
                        ->subject(trans('mail.subject.activity', ['id' => $ticket['id'], 'title' => str_limit($ticket['title'], 40)], $user['locale']));
                }
            );
        }
    }
}
