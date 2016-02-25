<?php namespace App\Repositories\Criteria\Tickets;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use App\Repositories\Criteria\Request\RequestCriteria;

class RequestWhereInStatus extends RequestCriteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $this->whereIn('status', $model);
    }
}