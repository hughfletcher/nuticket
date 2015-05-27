<?php namespace App\Http\Composers;

use App\Repositories\StaffInterface;

class StaffComposer {

	public function __construct(StaffInterface $staff) {
        $this->staff = $staff;
	}

    public function compose($view)
    {
        $view->with('staff', $this->staff->lists('display_name'));

    }

}