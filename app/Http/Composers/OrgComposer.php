<?php namespace App\Http\Composers;

use App\Contracts\Repositories\OrgInterface;

class OrgComposer {

	public function __construct(OrgInterface $org) {
        $this->org = $org;
	}

    public function compose($view)
    {
        $view->with('orgs', $this->org->findWhere(['active' => true]));

    }

}