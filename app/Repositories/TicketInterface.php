<?php namespace App\Repositories;

interface TicketInterface {

	public function paginate($per_page);

	public function whereCreated($start = null, $end = null, $table = null);

	public function whereSearch(array $query = [], array $cols = []);

	public function whereStatus(array $values);

	public function wherePriority(array $values);

	public function whereStaff(array $values);

	public function whereDept(array $values);

	public function whereUser($id = '*');

	public function create($attrs);
	
}