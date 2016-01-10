<?php namespace App\Repositories\Criteria\Tickets;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

/**
 * Class Tickets//WithAssigned
 *
 * @package App\Repositories\Criteria
 */
class WithAssigned extends Criteria {

    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->with('assigned');
    }
}
