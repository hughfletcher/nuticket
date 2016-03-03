<?php

use Tests\TestCase, Mockery as m;
use App\User;

class AuthServiceProviderTest extends TestCase {


	public function testBoot()
	{
		$this->assertTrue(Gate::forUser(factory(App\User::class)->make(['is_admin' => true]))->allows('self-destruct'));
		$this->assertFalse(Gate::forUser(factory(App\User::class)->make(['is_admin' => false]))->allows('self-destruct'));
		$this->assertTrue(Gate::forUser(factory(App\User::class)->make(['is_staff' => true]))->allows('use-tags'));
		$this->assertFalse(Gate::forUser(factory(App\User::class)->make(['is_staff' => false]))->allows('use-tags'));
		$this->assertTrue(Gate::forUser(factory(App\User::class)->make(['is_staff' => true]))->allows('isStaff'));
	}

}
