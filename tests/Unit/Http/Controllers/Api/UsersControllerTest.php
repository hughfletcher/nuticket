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
        // $local = factory(App\User::class, 200)->make();
        $event = factory(App\User::class, 200)->make();
 
        $request = m::mock('App\Http\Requests\UserQueryRequest')->makePartial();
        $request->shouldReceive('get')->once()->with('q');
        $request->shouldReceive('get')->once()->with('noevent');
        $request->shouldReceive('get')->once()->with('fields');
        $request->shouldReceive('all')->twice()->withNoArgs();

        Event::shouldReceive('fire')->once()->with(m::type('App\Events\UsersGetAllEvent'), [], false)->andReturn([$event]);
        $chain = m::mock();
        $chain->shouldReceive('all')->once();
        $this->user->shouldReceive('pushCriteria')
            ->once()
            ->with(m::type('App\Repositories\Criteria\Users\WhereNameLike'))
            ->andReturn($chain);

        $controller = new UsersController($this->user);
        $return = $controller->index($request);
        $this->assertEquals($event->toArray(), $return->getData(true));
    }

    public function testIndexEmptyEventData()
    {
        $local = factory(App\User::class, 200)->make();
 
        $request = m::mock('App\Http\Requests\UserQueryRequest')->makePartial();
        $request->shouldReceive('get')->once()->with('q');
        $request->shouldReceive('get')->once()->with('noevent');
        $request->shouldReceive('get')->once()->with('fields');
        $request->shouldReceive('all')->twice()->withNoArgs();

        Event::shouldReceive('fire')->once()->with(m::type('App\Events\UsersGetAllEvent'), [], false)->andReturn([]);
        $this->user->shouldReceive('pushCriteria->all')->once()->andReturn($local);

        $controller = new UsersController($this->user);
        $return = $controller->index($request);
        $this->assertEquals($local->toArray(), $return->getData(true));

    }

    public function testIndexNoEvent()
    {
        $local = factory(App\User::class, 200)->make();
 
        $request = m::mock('App\Http\Requests\UserQueryRequest')->makePartial();
        $request->shouldReceive('get')->once()->with('q');
        $request->shouldReceive('get')->once()->with('noevent')->andReturn(1);
        $request->shouldReceive('get')->once()->with('fields');
        $request->shouldReceive('all')->once()->withNoArgs();

        $this->user->shouldReceive('pushCriteria->all')->once()->andReturn($local);

        $controller = new UsersController($this->user);
        $return = $controller->index($request);
        $this->assertEquals($local->toArray(), $return->getData(true));
    }

    public function testShow()
    {
        $result = factory(App\User::class)->make();
        $this->user->shouldReceive('find')->once()->with(347)->andReturn($result);
        $controller = new UsersController($this->user);
        // $controller = new UsersController($this->user);
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
        $this->user->shouldReceive('update')->once()->with($submit, 217);
        $this->user->shouldReceive('find')->once()->with(217);
        $request = m::mock('App\Http\Requests\UserUpdateRequest');
        $request->shouldReceive('except')->once()->with('_method')->andReturn($submit);
        $controller = new UsersController($this->user);
        $controller->update($request, 217);
    }

    public function testDestroy()
    {
        $controller = new UsersController($this->user);
        $controller->destroy(217);
    }
}
