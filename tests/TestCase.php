<?php namespace Tests;

use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Mockery;

abstract class TestCase extends LaravelTestCase
{


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
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        parent::tearDown();
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

		return new \Illuminate\Pagination\LengthAwarePaginator($results, 1, 20);
	}
}
