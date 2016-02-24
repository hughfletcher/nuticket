<?php namespace App\Repositories\Criteria\Users;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class WhereNameLike extends Criteria {

    public function __construct($query) 
    {
        $this->query = $query;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
    	$query = explode('-', str_slug($this->query));

        if (count($query) === 1 && empty($query[0])) {
            return $model;
        }

		foreach ($query as $term) {

			$model = $model->where(function($q) use ($term) {

				$q->orWhere('display_name', 'LIKE', '%' . $term . '%')
					->orWhere('username', 'LIKE', '%' . $term . '%');

			});

		}

		return $model;
    }
}