<?php namespace App\Repositories\Eloquent;

use App\Repositories\ReportInterface;
use App\Report;

class ReportRepository implements ReportInterface {

	public function __construct(Report $report) {
		$this->reports = $report;
	} 

	public function all() {
		return $this->reports->all();
	}

	public function find($id) {
		return $this->reports->find($id);
	}
	// public function query($sql, $params = []) {

	// 	$params = array_map(function($value) {
	// 		return DB::getPdo()->quote($value);
	// 	}, $params);
		
	// 	return DB::select(DB::raw($this->parser->parse($sql, $params)));
	// }
	
}