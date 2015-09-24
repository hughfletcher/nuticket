<?php namespace App\Repositories;

use App\Contracts\Repositories\UserInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use App\Events\UsersGetAllEvent;
use App\Events\UserCreatedEvent;

class UserRepository extends Repository implements UserInterface, RepositoryInterface {

	public function model() {
        return 'App\User';
    }

    public function all($columns = ['*'], array $params = array(), $defer_listeners = false) 
    {
    	$this->pushCriteria(new Criteria\RequestSearchUsers($params));
    	$results = parent::all($columns);
    	$params['defer_listeners'] = $defer_listeners;

    	$event_data = event(new UsersGetAllEvent($results, $params));

    	foreach ($event_data as $data) {

    		foreach ($data as $row) {
    			$results->put($results->count(), $row);
    		}
    		
    	}
    	return $results;
    }

    public function create(array $data) 
    {
    	$user = parent::create($data);

    	event(new UserCreatedEvent($user));

    	return $user;
    }

	// public function lists($value, $key = 'id') {
	// 	return $this->user->lists($value, $key);
	// }

	// public function allWithTrashed() {
	// 	return $this->user->withTrashed()->get()->toArray();
	// }


	// public function get() {
	// 	return $this->model->get()->toArray();
	// }

	// public function destroy($id) {
	// 	$this->user->destroy($id);
	// }

	// public function insert($data) {
	// 	$row = $this->user->create($data);
	// 	return $row->id;
	// }

	// public function fields($cols) {

	// 	if (empty($cols)) {
	// 		return $this;
	// 	}

	// 	$this->model = $this->model->select($cols);

	// 	return $this;
	// }

	/**
	 * Search filter on query
	 * 
	 * @param  array $query
	 * @param  array $cols
	 * @return $this
	 */
	// public function whereSearch(array $query = [], array $cols = []) {	

	// 	$search = ['display_name'];

	// 	return parent::whereSearch($query, $search);
	// }

	// public function findBy($attribute, $value, $columns = array('*')) {

 //        return $this->model->where($attribute, '=', $value)->first($columns);
 //    }

}