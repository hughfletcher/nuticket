<?php namespace App\Repositories\Eloquent\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class Request extends Criteria {

    public function __construct($param)
    {
        $this->param = $param;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
    	$values = array_filter(explode('-', app('request')->get($this->param)));

    	if (!app('request')->has($this->param) || empty($values)) {
			return $model;
		}

		return $model->whereIn($model->getModel()->getTable() . '.' . $this->param, $values);
    }
}