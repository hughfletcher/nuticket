<?php namespace App\Repositories\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class RequestSearchUsers extends Criteria {

    public function __construct(array $params) 
    {
        $this->params = $params;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
    	$query = isset($this->params['q']) ? explode('-', str_slug($this->params['q'])) : [];


		foreach ($query as $term) {

			$model = $model->where(function($q) use ($term) {

				$q->orWhere('display_name', 'LIKE', '%' . $term . '%')
					->orWhere('username', 'LIKE', '%' . $term . '%');

			});

		}

		return $model;
    }
}