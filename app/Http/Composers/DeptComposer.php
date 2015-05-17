<?php namespace App\Http\Composers;

use App\Repositories\DeptInterface;

class DeptComposer {

	public function __construct(DeptInterface $dept) {
        $this->dept = $dept;
	}

    public function compose($view)
    {
        $view->with('depts', $this->dept->lists('name'));

    }

}