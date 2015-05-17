<?php namespace App\Repositories\Eloquent;

use App\Repositories\UserInterface;
use App\User;

class UserRepository extends BaseRepository implements UserInterface {

	public function __construct(User $user) {

		$this->user = $user;
		$this->model = $user;
	}

	public function lists($value, $key = 'id') {
		return $this->user->lists($value, $key);
	}

	public function allWithTrashed() {
		return $this->user->withTrashed()->get()->toArray();
	}

	public function all() {
		return $this->model->all()->toArray();
	}

	public function get() {
		return $this->model->get()->toArray();
	}

	public function destroy($id) {
		$this->user->destroy($id);
	}

	public function insert($data) {
		$row = $this->user->create($data);
		return $row->id;
	}

	public function fields($cols) {

		if (empty($cols)) {
			return $this;
		}

		$this->model = $this->model->select($cols);

		return $this;
	}

	/**
	 * Search filter on query
	 * 
	 * @param  array $query
	 * @param  array $cols
	 * @return $this
	 */
	public function whereSearch(array $query = [], array $cols = []) {	

		$search = ['display_name'];

		return parent::whereSearch($query, $search);
	}
}