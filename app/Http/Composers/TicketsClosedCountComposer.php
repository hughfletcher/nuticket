<?php namespace App\Http\Composers;

use App\Contracts\Repositories\TicketInterface;

class TicketsClosedCountComposer {

	public function __construct(TicketInterface $ticket)
	{
		$this->ticket = $ticket;
	}

    public function compose($view)
    {
        $view->with('close_count', $this->ticket->findAllBy('status', 'closed')->count());
    }

}
