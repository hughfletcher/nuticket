<?php

namespace App\Listeners;

use App\Events\ActionCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyActionCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActionCreatedEvent  $event
     * @return void
     */
    public function handle(ActionCreatedEvent $event)
    {
        //
    }
}
