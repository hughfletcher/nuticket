<?php namespace Tests\Unit\Http\Requests;

use Tests\TestCase;
use App\Http\Requests\TicketIndexRequest;

class TicketIndexRequestTest extends TestCase
{

    public function testRules()
    {
        $request = new TicketIndexRequest;
        $rules = $request->rules();
        $this->assertCount(5, $rules);
        $this->assertArrayHasKey('status', $rules);
        $this->assertArrayHasKey('assigned_id', $rules);
    }

    public function testSort()
    {
        $request = new TicketIndexRequest;
        $sorts = $request->sort();
        $this->assertCount(7, $sorts);
        $this->assertTrue(in_array('created_at', $sorts));
    }
}
