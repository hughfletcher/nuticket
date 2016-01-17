<?php namespace App\Services\Reports;

use Illuminate\Foundation\Application;

interface SourceInterface 
{
	public function __construct(Application $app);
	public function run($sql);
}