<?php namespace App\Repositories\Eloquent\Criteria;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Illuminate\Http\Request;

class RequestSort extends Criteria {

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->orderBy(app('request')->get('sort', 'id'), app('request')->get('order', 'desc'));
    }
}