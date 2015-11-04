<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Ticket;

class TicketCreatedEvent extends Event
{
    use SerializesModels;

    public $ticket;
    public $summary;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, $action_summary = false)
    {
        $this->ticket = $ticket;
        $this->summary = $action_summary;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
