<?php

use App\Providers\LogServiceProvider;

class LogServiceProviderTest extends TestCase {


	public function testBoot()
	{
		$this->app['config']->set('settings.log.level', 'emergency');
		$provider = new LogServiceProvider($this->app);
		$provider->boot();

		$monolog = $this->app['log']->getMonolog();
        foreach($monolog->getHandlers() as $handler) {
            $this->assertEquals(600, $handler->getLevel());
        }
    }

}
