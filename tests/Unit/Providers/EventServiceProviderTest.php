<?php

use Tests\TestCase, Mockery as m;
use App\User;
use Faker\Factory as Faker;

class EventServiceProviderTest extends TestCase {


	public function testMailSending()
	{
		$this->app['config']->set('settings.log.level', 'debug');
		Log::shouldReceive('debug')->once();
		$faker = Faker::create();
		$this->app['mailer']->raw($faker->paragraph, function ($message) use ($faker) {
                $message->from($faker->email, $faker->name)
                    ->to($faker->email, $faker->name)
                    ->subject($faker->sentence);
            }
        );
    }

}
