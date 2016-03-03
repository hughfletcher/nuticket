<?php namespace App\Repositories\Criteria\Tickets;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

/**
 * Class WithAll
 *
 * @package App\Repositories\Criteria
 */
class WithAll extends Criteria 
{
    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->with(
            [
                'assigned',
                'dept',
                'dept.members', 
                'org', 
                'user', 
                'actions' => function ($query) {

                    $query->with('assigned', 'user', 'transfer');

                }
            ]
        );
    }
}