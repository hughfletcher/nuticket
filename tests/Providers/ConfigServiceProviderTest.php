<?php namespace Tests\Providers;

use TestCase, Mockery as m;
use App\Providers\ConfigServiceProvider;
use App\Config;

class ConfigServiceProviderTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$db = m::mock();
		$db->shouldReceive('connection->getSchemaBuilder->hasTable')->andReturn(true);
		$this->app->instance('db', $db);

		$result[1] = new Config(['key' => 'system.eyes', 'value' => 'blue', 'enviroment' => 'production']);
		$result[1]->id = 1;
		$result[2] = new Config(['key' => 'system.hair', 'value' => 'brunette', 'enviroment' => 'production']);
		$result[2]->id = 2;
		$result[3] = new Config(['key' => 'system.hottie', 'value' => false, 'enviroment' => 'production']);
		$result[3]->id = 3;

		$this->config = m::mock();
		$this->config->shouldReceive('findAllBy')->once()->andReturn($result);
		$this->app->instance('App\Repositories\ConfigInterface', $this->config);
	}

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBootDbOverwitesConfig()
	{
		
		$this->app['config']->set('system.hottie', true);

		$csp = new ConfigServiceProvider($this->app);
		$csp->boot();

		$this->assertEquals(false, $this->app['config']->get('system.hottie'));
	}

	public function testBootDeletesDbConfig()
	{
		
		$this->app['config']->set('system.hottie', false);

		$this->config->shouldReceive('delete')->with(3)->once();
		$this->app->instance('App\Repositories\ConfigInterface', $this->config);

		$csp = new ConfigServiceProvider($this->app);
		$csp->boot();

		// $this->assertTrue($this->app['config']->get('system.hottie'));
	}

	// public function testBootNoConfigTable() 
	// {
	// 	$db = m::mock();
	// 	$db->shouldReceive('connection->getSchemaBuilder->hasTable')->andReturn(true);
	// 	$this->app->instance('db', $db);

	// 	$csp = new ConfigServiceProvider($this->app);

	// 	$this->assertNull($csp->boot());
	// }

}