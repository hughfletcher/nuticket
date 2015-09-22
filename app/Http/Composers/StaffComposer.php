<?php namespace App\Http\Composers;

use App\Contracts\Repositories\StaffInterface;

class StaffComposer {

	public function __construct(StaffInterface $staff) {
        $this->staff = $staff;
	}

    public function compose($view)
    {
        $view->with('staff', $this->staff->all(['display_name', 'id'])->lists('display_name', 'id'));

    }

}