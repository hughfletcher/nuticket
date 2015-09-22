<?php namespace App\Repositories;

use App\Contracts\Repositories\StaffInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class StaffRepository extends Repository implements StaffInterface {

	public function model() {
        return 'App\Staff';
    }

	public function all($columns = array('*')) 
	{
		if ($dn = array_search('display_name', $columns) !== true) {
			$columns[$dn] = 'users.display_name as display_name';
			$columns[array_search('id', $columns)] = 'staff.id as id';
			$this->model = $this->model->join('users', 'users.id', '=', 'staff.user_id');
		}

		return $this->model->get($columns);
	}



}