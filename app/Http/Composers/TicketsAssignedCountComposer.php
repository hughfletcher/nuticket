<?php namespace App\Http\Composers;

use App\Contracts\Repositories\TicketInterface;
use Illuminate\Foundation\Application;
use App\Repositories\Criteria\Tickets\StatusOpenOrNew;

class TicketsAssignedCountComposer {

	public function __construct(TicketInterface $ticket, Application $app)
	{
		$this->ticket = $ticket;
		$this->app = $app;
	}

    public function compose($view)
    {
        $view->with('assigned_count',
			$this->ticket->pushCriteria(new StatusOpenOrNew)
				->findWhere(['assigned_id' => $this->app['auth']->user()->id])
				->count()
		);
    }

}
