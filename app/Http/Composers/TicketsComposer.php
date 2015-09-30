<?php namespace App\Http\Composers;

use Illuminate\Foundation\Application;
use App\Ticket;
use App\TicketDept;

class TicketsComposer {

	public function __construct(Application $app) {
		$this->app = $app;
	}

    public function compose($view)
    {
        $view->with('is_staff', true);
        // $view->with('is_staff', ($this->app['auth']->user()->staff ? true : false));

        $view->with('open_count', Ticket::getOpenCount());
        // $view->with('open_count', 45);
        $view->with('close_count', Ticket::getClosedCount());
        // $view->with('close_count', 30);
        $view->with('assigned_count', Ticket::getAssignedCount($this->app['auth']->user()->is_staff));
        // $view->with('assigned_count', 15);

        $view->with('priorities', [
            '1' => '1 - Business is stopped',
            '2' => '2 - User is stopped',
            '3' => '3 - Business is hendered',
            '4' => '4 - User is hendered',
            '5' => '5 - Increase productivity/savings',
        ]);
    }

}