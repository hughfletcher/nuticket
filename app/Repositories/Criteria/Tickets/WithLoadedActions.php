<?php namespace App\Repositories\Criteria\Tickets;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

/**
 * Class Tickets//WithConstrainedActions
 *
 * @package App\Repositories\Criteria
 */
class WithLoadedActions extends Criteria
{

    public function __construct(array $keys = [])
    {
        $this->keys = $keys;
    }
    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->with(['actions' => function ($query) {

            $query->with('assigned', 'user', 'transfer');

            if (!empty($this->keys)) {
                $query->whereIn('id', $this->keys);
            }

        }]);
    }
}
