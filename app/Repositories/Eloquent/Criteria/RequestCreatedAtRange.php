<?php namespace App\Repositories\Eloquent\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RequestCreatedAtRange extends Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
    	$dates = app('request')->has('created_at') ? explode('-', app('request')->get('created_at')) : [null, null];

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