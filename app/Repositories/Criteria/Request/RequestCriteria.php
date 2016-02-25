<?php namespace App\Repositories\Criteria\Request;

use Bosnadev\Repositories\Criteria\Criteria;
use App\Http\Requests\QueryRequest;

abstract class RequestCriteria extends Criteria {

	public function __construct(QueryRequest $request)
	{
		$this->request = $request;
	}

	public function whereIn($param, $model)
	{
		$values = $this->request->get($param);

    	if (!$this->request->has($param) || empty($values)) {
			return $model;
		}

		return $model->whereIn($model->getModel()->getTable() . '.' . $param, $values);
	}
}