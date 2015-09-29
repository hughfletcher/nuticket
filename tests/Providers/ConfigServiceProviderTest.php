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

		$result = $this->mockEloquentResults('App\Config', [
			['key' => 'system.eyes', 'value' => 'blue', 'enviroment' => 'production', 'id' => 1],
			['key' => 'system.hair', 'value' => 'brunette', 'enviroment' => 'production', 'id' => 2],
			['key' => 'system.hottie', 'value' => 0, 'enviroment' => 'production', 'id' => 3]
		]);



		$this->config = m::mock('App\Repositories\Eloquent\ConfigRepository');
		$this->config->shouldReceive('findAllBy')->atMost(1)->andReturn($result);
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

		$this->assertFalse($this->app['config']->get('system.hottie'));
	}

	public function testBootNoConfigTable() 
	{
		$db = m::mock();
		$db->shouldReceive('connection->getSchemaBuilder->hasTable')->andReturn(false);
		$this->app->instance('db', $db);

		$csp = new ConfigServiceProvider($this->app);

		$this->assertNull($csp->boot());
	}

}