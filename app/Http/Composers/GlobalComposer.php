<?php namespace App\Http\Composers;

use Illuminate\Foundation\Application;
use App\Ticket;
use App\Staff;
use App\TicketDept;

class GlobalComposer {

	public function __construct(Application $app) {
		$this->app = $app;
	}

    public function compose($view)
    {
        // $view->with('staff_id', function() {
        //     return 12;
        // });

    }

}