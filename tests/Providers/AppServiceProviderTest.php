<?php namespace Tests\Providers;

use TestCase, Mockery as m;
use App\Providers\AppServiceProvider;

class AppServiceProviderTest extends TestCase {


	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testDevelopInLocal()
	{
		$app = m::mock();

		$app->shouldReceive('environment')->once()->andReturn(true);		
		$app->shouldReceive('register')->once()
    		->with(m::on(function($class) {
        		$this->assertEquals('Clockwork\Support\Laravel\ClockworkServiceProvider', $class);
        		return true;
    	}));

    	$asp = new AppServiceProvider($app);
    	$asp->develop();
	}

	public function testDevelopNotLocal()
	{
		$app = m::mock();

		$app->shouldReceive('environment')->once()->andReturn(false);		
		$app->shouldReceive('register')->never();

    	$asp = new AppServiceProvider($app);
    	$asp->develop();
	}



}