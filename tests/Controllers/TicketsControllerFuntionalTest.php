<?php namespace Tests\Controllers;

use Tests\TestCase;
use Mockery as m;
use App\User;
use App\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Artisan;

class TicketControllerFunctionalTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }

    public function testAllRequiresLogin()
    {
        $this->visit('/tickets')->seePageIs('/session/create');
        $this->visit('/tickets/create')->seePageIs('/session/create');
        $this->visit('/tickets/290/edit')->seePageIs('/session/create');
        $this->visit('/tickets/146')->seePageIs('/session/create');
    }

    public function testIndex()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets')
            ->seePageIs('/tickets')
            ->assertViewHas(['open_count', 'close_count', 'assigned_count', 'tickets']);
    }

    public function testShow()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/261')
            ->seePageIs('/tickets/261')
            ->assertViewHas(['staff', 'depts', 'ticket']);
    }

    public function testCreateNoUserRequested()
    {
        // $request = m::mock('App\Http\Requests\TicketCreateRequest');
        // $request->shouldReceive('has')->once()->andReturn(false);
        // $this->app->instance('App\Http\Requests\TicketCreateRequest', $request);

        $user = new User(['is_staff' => 0, 'display_name' => 'Hugh Fletcher']);

        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/create')
            ->seePageIs('/tickets/create')
            ->assertViewMissing('user');
    }

    public function testCreateUserRequested()
    {
        // $userModel = factory(User::class)->make();
        // $userStaff = factory(User::class, 'staff', 10)->make();
        // $user = m::mock('App\Contracts\Repositories\UserInterface');
        // $user->shouldReceive('find')->once()->andReturn($userModel);
        // $user->shouldReceive('findAllBy')->once()->andReturn($userStaff);
        // $this->app->instance('App\Contracts\Repositories\UserInterface', $user);
        //
        // $request = m::mock('App\Http\Requests\TicketCreateRequest');
        // $request->shouldReceive('has')->with('user_id')->once()->andReturn(true);
        // $request->shouldReceive('get')->with('user_id')->once()->andReturn(315);
        // $this->app->instance('App\Http\Requests\TicketCreateRequest', $request);

        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/create?user_id=56')
            ->seePageIs('/tickets/create?user_id=56')
            ->assertViewHas('user');
    }

    public function testCreateUserRequestedButDoesNotExist()
    {
        $this->actingAs(factory(User::class, 'staff')->make())
            ->visit('/tickets/create?user_id=241')
            ->seePageIs('/tickets/create?user_id=241')
            ->assertViewMissing('user');
    }
}
