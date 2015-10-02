<?php namespace App\Http\Composers;

use App\Contracts\Repositories\TicketInterface;
use App\Repositories\Criteria\Tickets\StatusOpenOrNew;

class TicketsOpenCountComposer {

	public function __construct(TicketInterface $ticket)
	{
		$this->ticket = $ticket;
	}

    public function compose($view)
    {
        $view->with('open_count', $this->ticket->pushCriteria(new StatusOpenOrNew)->all()->count());
    }

}
