<?php namespace App\Services\Reports;

use Illuminate\Foundation\Application;

class DefaultSource implements SourceInterface
{
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	public function run($sql)
	{
		return $this->app['db']->select($sql);
	}
}