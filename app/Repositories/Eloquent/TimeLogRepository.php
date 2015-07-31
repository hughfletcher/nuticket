<?php namespace App\Repositories\Eloquent;

use App\Repositories\TimeLogInterface;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class TimeLogRepository extends Repository implements TimeLogInterface {

	public function model() {
        return 'App\TimeLog';
    }

    public function paginateByUser($id, $perPage = 15) 
    {
    	return $this->model->with('action.ticket')->where('user_id', $id)->orderBy('time_at', 'desc')->paginate($perPage);
    }

}