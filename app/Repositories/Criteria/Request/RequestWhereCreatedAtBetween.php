<?php namespace App\Repositories\Criteria\Request;

use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Carbon\Carbon;

class RequestWhereCreatedAtBetween extends RequestCriteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
    	$dates = $this->request->has('created_at') ? explode('-', $this->request->get('created_at')) : [null, null];

		if (!$dates[0] || !$dates[1]) {
			return $model;
		}

		$start = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay()->toDateTimeString();
		$end = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay()->toDateTimeString();
		
		return $model
			->where($model->getModel()->getTable() . '.created_at', '>', $start)
			->where($model->getModel()->getTable() . '.created_at', '<', $end);
    }
}