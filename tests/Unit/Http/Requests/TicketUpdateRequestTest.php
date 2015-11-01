<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Mockery as m;
use App\Http\Requests\TicketUpdateRequest;

class TicketUpdateRequestTest extends TestCase
{
    public function testAuthorize()
    {
        $request = new TicketUpdateRequest;
        $auth = $request->authorize();
        $this->assertTrue($auth);
    }

    public function testRules()
    {
        $request = new TicketUpdateRequest;
        $rules = $request->rules();
        $this->assertEquals(5, count($rules));
        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('priority', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('body', $rules);
        $this->assertArrayHasKey('reason', $rules);
    }
}
