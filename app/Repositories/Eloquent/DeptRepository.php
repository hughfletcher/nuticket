<?php namespace App\Repositories\Eloquent;

use App\Repositories\DeptInterface;
use App\Dept;

class DeptRepository implements DeptInterface {

	public function __construct(Dept $dept) {

		$this->dept = $dept;
	}

	public function lists($value, $key = 'id') {
		return $this->dept->lists($value, $key);
	}
}