<?php namespace App\Http\Composers;

use App\Contracts\Repositories\UserInterface;

class StaffComposer {

	public function __construct(UserInterface $user) {
        $this->user = $user;
	}

    public function compose($view)
    {
        $view->with('staff', $this->user->findAllBy('is_staff', true, ['display_name', 'id'])->lists('display_name', 'id'));

    }

}