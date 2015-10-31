<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Mockery as m;
use App\Http\Requests\Settings\SystemUpdateRequest as Request;

class SystemUpdateRequestTest extends TestCase
{
    public function setUp()
    {
        $this->gate = m::mock('Illuminate\Contracts\Auth\Access\Gate');
    }

    public function testAuthorize()
    {
        $this->gate->shouldReceive('allows')->once()->with('manage_settings_system')->andReturn(true);
        $request = new Request($this->gate);
        $this->assertEquals(true, $request->authorize());

    }

    public function testRules()
    {
        $request = new Request($this->gate);
        $rules = $request->rules();
        $this->assertEquals(4, count($rules));
        $this->assertArrayHasKey('system_title', $rules);
        $this->assertArrayHasKey('system_pagesize', $rules);
        $this->assertArrayHasKey('system_format_date', $rules);
        $this->assertArrayHasKey('system_format_dateday', $rules);
    }
}
