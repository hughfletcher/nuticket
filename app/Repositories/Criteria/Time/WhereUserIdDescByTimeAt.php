<?php namespace App\Repositories\Criteria\Time;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

/**
 * Class WhereUserIdOrderByDate
 *
 * @package App\Repositories\Criteria
 */
class WhereUserIdDescByTimeAt extends Criteria {

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->where('user_id', $this->id)->orderBy('time_at', 'desc');
    }
}