<?php namespace App\Repositories\Criteria\Request;

use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use App\Repositories\Criteria\Request\RequestCriteria;

class RequestWhereInOrgId extends RequestCriteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $this->whereIn('org_id', $model);
    }
}