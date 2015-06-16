<?php namespace App\Repositories\Eloquent;

use App\Repositories\TimeLogInterface;
use App\TimeLog;

class TimeLogRepository implements TimeLogInterface {

	public function __construct(TimeLog $timelog) 
	{
		$this->timelog = $timelog;
	}

	public function create($attrs) 
	{
		return $this->timelog->create($attrs);
	}
}