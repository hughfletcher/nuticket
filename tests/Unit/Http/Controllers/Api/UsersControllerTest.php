<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\Api\UsersController;
use Mockery as m;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->user = m::mock('App\Contracts\Repositories\UserInterface');
    }
    public function testIndex()
    {
        $this->user->shouldReceive('all')->once()->with(['display_name', 'id'], ['per_page' => 23], false)->andReturn(factory(App\User::class, 10));
        $request = m::mock('App\Http\Requests\UserQueryRequest');
        $request->shouldReceive('get')->with('fields')->once()->andReturn('display_name,id');
        $request->shouldReceive('all')->once()->andReturn(['per_page' => 23]);

        $controller = new UsersController($this->user);
        $controller->index($request);
    }

    public function testShow()
    {
        $result = factory(App\User::class)->make();
        $this->user->shouldReceive('find')->once()->with(347)->andReturn($result);
        $controller = new UsersController($this->user);
        $response = $controller->show(347);
        $this->assertEquals($response, response()->json($result));
    }

    public function testStoreWithDisplayName()
    {
        $submit = factory(App\User::class)->make();
        $request = m::mock('App\Http\Requests\UserStoreRequest');
        $request->shouldReceive('exists')->once()->with('display_name')->andReturn(true);
        $request->shouldReceive('all')->once()->withNoArgs()->andReturn($submit->toArray());
        $this->user->shouldReceive('create')->once()->with($submit->toArray())->andReturn($submit);
        $controller = new UsersController($this->user);
        $response = $controller->store($request);
        $this->assertEquals($response, $submit);
    }

    public function testStoreWithNoDisplayName()
    {
        $submit = factory(App\User::class)->make();
        $request = m::mock('App\Http\Requests\UserStoreRequest');
        $request->shouldReceive('exists')->once()->with('display_name')->andReturn(false);
        $request->shouldReceive('has')->once()->with('first_name')->andReturn(false);
        $request->shouldReceive('has')->once()->with('last_name')->andReturn(true);
        $request->shouldReceive('input')->twice()->andReturn('Bob');
        $request->shouldReceive('merge')->once()->with(['display_name' => 'Bob Bob']);

        $request->shouldReceive('all')->once()->withNoArgs()->andReturn($submit->toArray());
        $this->user->shouldReceive('create')->once()->with($submit->toArray())->andReturn($submit);
        $controller = new UsersController($this->user);
        $response = $controller->store($request);
        $this->assertEquals($response, $submit);
    }

    public function testUpdate()
    {
        $submit = factory(App\User::class)->make()->toArray();
        $this->user->shouldReceive('update')->once()->with(217, $submit);
        $request = m::mock();
        $request->shouldReceive('all')->once()->withNoArgs()->andReturn($submit);
        $controller = new UsersController($this->user);
        $controller->update($request, 217);
    }

    public function testDestroy()
    {
        $controller = new UsersController($this->user);
        $controller->destroy(217);
    }
}
