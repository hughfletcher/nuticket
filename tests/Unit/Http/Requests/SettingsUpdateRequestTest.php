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
        $this->gate = m::mock('Illuminate\Contracts\Auth\Access\Gate');
    }

    public function testAuthorize()
    {
        $this->gate->shouldReceive('allows')->once()->with('manage_settings_system')->andReturn(true);
        $request = new SettingsUpdateRequest($this->gate);
        $this->assertEquals(true, $request->authorize());

    }

    public function testSystemRules()
    {
        $request = m::mock('App\Http\Requests\SettingsUpdateRequest[segment]', [$this->gate]);
        $request->shouldReceive('segment')->with(2)->andReturn('system');
        $rules = $request->rules();

        $this->assertEquals(4, count($rules));
        $this->assertArrayHasKey('system_title', $rules);
        $this->assertArrayHasKey('system_pagesize', $rules);
        $this->assertArrayHasKey('system_format_date', $rules);
        $this->assertArrayHasKey('system_format_dateday', $rules);
    }

    public function testMailRules()
    {
        $request = m::mock('App\Http\Requests\SettingsUpdateRequest[segment]', [$this->gate]);
        $request->shouldReceive('segment')->with(2)->andReturn('emails');
        $rules = $request->rules();

        $this->assertEquals(7, count($rules));
        $this->assertArrayHasKey('mail_default', $rules);
        $this->assertArrayHasKey('mail_admin', $rules);
        $this->assertArrayHasKey('mail_fetching', $rules);
        $this->assertArrayHasKey('mail_replyseperator', $rules);
        $this->assertArrayHasKey('mail_acceptunknown', $rules);
        $this->assertArrayHasKey('mail_keeppriority', $rules);
        $this->assertArrayHasKey('mail_defaultmta', $rules);
    }
}
