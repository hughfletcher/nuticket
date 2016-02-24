<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Mockery as m;
use App\Http\Requests\SettingsUpdateRequest;

class SettingsUpdateRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gate = m::mock('Illuminate\Contracts\Auth\Access\Gate');
    }

    public function testAuthorize()
    {
        $this->gate->shouldReceive('allows')->once()->with('manage_settings_system')->andReturn(true);
        $request = m::mock('App\Http\Requests\SettingsUpdateRequest[segment]', [$this->gate]);
        $request->shouldReceive('segment')->with(2)->andReturn('system');
        $this->assertEquals(true, $request->authorize());

    }

    public function testSystemRules()
    {
        $request = m::mock('App\Http\Requests\SettingsUpdateRequest[segment]', [$this->gate]);
        $request->shouldReceive('segment')->with(2)->andReturn('system');
        $rules = $request->rules();

        $this->assertEquals(11, count($rules));
    }

    public function testMailRules()
    {
        $request = m::mock('App\Http\Requests\SettingsUpdateRequest[segment]', [$this->gate]);
        $request->shouldReceive('segment')->with(2)->andReturn('emails');
        $rules = $request->rules();

        $this->assertEquals(5, count($rules));
    }

    public function testNotificationsRules()
    {
       $request = m::mock('App\Http\Requests\SettingsUpdateRequest[segment]', [$this->gate]);
        $request->shouldReceive('segment')->with(2)->andReturn('notifications');
        $rules = $request->rules();

        $this->assertEquals(26, count($rules));
    }
}
