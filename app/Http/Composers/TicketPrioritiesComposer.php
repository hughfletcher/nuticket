<?php namespace App\Http\Composers;

class TicketPrioritiesComposer {

    public function compose($view)
    {

        $view->with('priorities', [
            '1' => '1 - Business is stopped',
            '2' => '2 - User is stopped',
            '3' => '3 - Business is hendered',
            '4' => '4 - User is hendered',
            '5' => '5 - Increase productivity/savings',
        ]);
    }

}
