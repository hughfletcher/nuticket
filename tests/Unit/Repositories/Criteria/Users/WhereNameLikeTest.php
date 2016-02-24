<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\Criteria\Users\WhereNameLike;
use Mockery as m;
use Tests\TestCase;
use App\User;

class WhereNameLikeTest extends TestCase
{
	public function setUp()
    {
        parent::setUp();
        $this->model = new User;
        $this->repo = m::mock('Bosnadev\Repositories\Contracts\RepositoryInterface');
    }

	public function testApplyOneWord()
	{
		$wnl = new WhereNameLike('boo');
		$return = $wnl->apply($this->model, $this->repo);

		$this->assertEquals($return->toSql(), 'select * from "users" where "users"."deleted_at" is null and ("display_name" LIKE ? or "username" LIKE ?)');
		$this->assertEquals($return->getQuery()->getBindings(), ["%boo%", "%boo%"]);

	}

	public function testApplyMultipleWords()
	{
		$wnl = new WhereNameLike('Neque porro quisquam est qui');
		$return = $wnl->apply($this->model, $this->repo);

		$this->assertEquals(10, count($return->getQuery()->getBindings()));
	}

	public function testApplyNothing()
	{
		$wnl = new WhereNameLike('');
		$return = $wnl->apply($this->model, $this->repo);

		$this->assertEquals(0, count($return->getQuery()->getBindings()));
	}

	public function testApplyNull()
	{
		$wnl = new WhereNameLike(null);
		$return = $wnl->apply($this->model, $this->repo);

		$this->assertEquals(0, count($return->getQuery()->getBindings()));
	}
}