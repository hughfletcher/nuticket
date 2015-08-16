<?php namespace App\Repositories\Eloquent;

use App\Repositories\StaffInterface;
use App\Staff;

class StaffRepository implements StaffInterface {

	public function __construct(Staff $staff) {

		$this->staff = $staff;
	}

	public function lists($value, $key = 'id') {
		return $this->staff->all()->lists($value, $key)->toArray();
	}
}