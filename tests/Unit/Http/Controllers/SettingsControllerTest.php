<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Http\Controllers\SettingsController;
use Mockery as m;
use App\Jobs\UpdateConfigJob;

class SettingsControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSystemEdit()
    {
        View::shouldReceive('make')->once()->with('settings.system', [], []);
        $edit = new SettingsController;
        $edit->edit('system');
    }

    public function testSettingsUpdate()
    {
        $request = m::mock('App\Http\Requests\SettingsUpdateRequest');
        $request->shouldReceive('except')->once()->with('_token', '_method')->andReturn([]);

        $this->expectsJobs(App\Jobs\UpdateConfigJob::class);
        $edit = new SettingsController;
        $response = $edit->update($request, 'system');

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
        $this->assertEquals($this->app['url']->to('settings/system'), $response->headers->get('Location'));
        $this->assertSessionHasAll(['message' => 'System settings were successfully updated.']);
    }
}
