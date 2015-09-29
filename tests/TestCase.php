<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase {

	protected $baseUrl = 'http://localhost';

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';

		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		return $app;
	}

	public function tearDown() 
	{
		parent::tearDown();

		Mockery::close();
	}

	public function mockEloquentResults($model, $data) 
	{
		$results = array();

		foreach ($data as $row => $row_data) {
			
			$model = new $model;

			foreach ($row_data as $key => $value) {
				
				$model->$key = $value;
			}

			$results[] = $model;

		}

		return $results;
	}

}
