<?php

namespace App\Listeners;

use App\Events\ActionCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Contracts\Repositories\UserInterface;
use Illuminate\Mail\Mailer;

class NotifyActionCreatedListener implements ShouldQueue
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
     * @param  ActionCreatedEvent  $event
     * @return void
     */
    public function handle(ActionCreatedEvent $event)
    {
        $staff = $this->user->findWhere(['username' => 'hfletcher']);
        $action = $event->action;
        
        foreach ($staff as $user) {
            $this->mailer->send(
                ['text' => 'mail.new_action'],
                ['user' => $user, 'action' => $action],
                function ($message) use ($user, $action) {
                    $message->from('support@tennesseetractor.com', 'IT Support')
                        ->to($user->email, $user->display_name)
                        ->subject('[#' . $action->ticket_id . '] ' . 'New Ticket Activity Alert');
                }
            );
        }
    }
}
