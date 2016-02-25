<?php namespace App\Repositories\Criteria\Request;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class RequestOrderBy extends RequestCriteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->orderBy($model->getModel()->getTable() . '.' . $this->request->get('sort', 'id'), $this->request->get('order', 'desc'));
    }
}