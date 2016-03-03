<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\Criteria\Users\WhereNotify;
use Mockery as m;
use Tests\TestCase;
use App\User;

class WhereNotifyTest extends TestCase
{
	public function setUp()
    {
        parent::setUp();
        $this->user = new User;
        $this->repo = m::mock('Bosnadev\Repositories\Contracts\RepositoryInterface');

        $this->config = [
        	'zombies' => [
        		'admin' => true,
        		'mgr' => true,
        		'owner' => true,
        		'dept' => false
        	],
        	'aliens' => [
        		'admin' => false,
        		'dept' => true,
        		'assigned' => true,
        		'org' => true
        	],
        	'monsters' => [
        		'admin' => false,
        		'dept' => false,
        		'last' => true
        	],
        	'vampires' => []
        ];

        $this->app['config']->set('settings.notify', $this->config);
        $this->app['config']->set('settings.autorespond.bymail', false);
    	$this->ticket = factory(App\Ticket::class)->make(['id' => 1234]);
    	$this->ticket->dept = factory(App\Dept::class)->make();
    }

    public function testApplyZombies()
    {
    	$this->ticket->events = factory(App\TicketAction::class, 2)->make([
    		'id' => 5678, 
    		'type' => 'zombies',
    		'source' => 'mail'
    	]);
    	$criteria = new WhereNotify($this->ticket);
    	$model = $criteria->apply($this->user, $this->repo);
    	$bindings = $model->getQuery()->getBindings();

    	$this->assertContains(true, $bindings);
    	$this->assertContains($this->ticket->dept->mgr_id, $bindings);
    	$this->assertContains($this->ticket->user_id, $bindings);
    	$this->assertCount(3, $bindings);
    }

    public function testApplyAliens()
    {
    	$this->ticket->events = factory(App\TicketAction::class, 2)->make([
    		'id' => 5678, 
    		'type' => 'aliens'
    	]);
    	$this->ticket->dept->members = factory(App\User::class, 'testing', 5)->make();
    	$criteria = new WhereNotify($this->ticket);
    	$model = $criteria->apply($this->user, $this->repo);
    	$bindings = $model->getQuery()->getBindings();

    	$this->assertArraySubset($this->ticket->dept->members->lists('id')->toArray(), $bindings);
    	$this->assertCount(6, $bindings);
    }

    public function testApplyLast()
    {
    	$this->app['config']->set('settings.autorespond.bymail', true);
    	$this->ticket->events = factory(App\TicketAction::class, 2)->make([
    		'id' => 5678, 
    		'type' => 'monsters',
    		'source' =>'mail'
    	]);
    	$this->ticket->actions = factory(App\TicketAction::class, 3)->make();
    	$last_user = $this->ticket->actions->last()->user_id;
    	$this->ticket->actions->push($this->ticket->events->first());

    	$criteria = new WhereNotify($this->ticket);
    	$model = $criteria->apply($this->user, $this->repo);
    	$bindings = $model->getQuery()->getBindings();
    	
    	$this->assertContains($last_user, $bindings);
    	$this->assertContains($this->ticket->events->first()->user_id, $bindings);
    	$this->assertCount(2, $bindings);
    }

    public function testApplyNone()
    {
    	$this->ticket->events = factory(App\TicketAction::class, 2)->make([
    		'id' => 5678, 
    		'type' => 'vampires'
    	]);
    	
    	$criteria = new WhereNotify($this->ticket);
    	$model = $criteria->apply($this->user, $this->repo);
    	$bindings = $model->getQuery()->getBindings();
    	$this->assertCount(1, $bindings);
    }
}