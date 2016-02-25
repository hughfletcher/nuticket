<?php namespace App\Repositories\Criteria\Request;

use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use App\Repositories\Criteria\Request\RequestCriteria;

class RequestWhereInDeptId extends RequestCriteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $this->whereIn('dept_id', $model);
    }
}