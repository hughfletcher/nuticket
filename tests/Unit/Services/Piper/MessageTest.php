<?php

use Tests\TestCase;
use Mockery as m;
use App\Services\Piper\Message;
use EmailReplyParser\Parser\EmailParser;
use Faker\Factory as Faker;

class MessageTest extends TestCase 
{
	public function setUp()
	{
		parent::setUp();
		$this->parser = new EmailParser;
		$this->user = m::mock('App\Services\Piper\Pipes\UserPipe');
		$this->org = m::mock('App\Contracts\Repositories\OrgInterface');
	}

	public function testGetSkinny()
	{
		$msg = $this->createMessageParts(['user']);
		$message = $this->createMessage($msg);
		$this->assertEquals($msg['body'], $message->getSkinny());
	}

	public function testGetSkinnyNoBody()
	{
		$msg = $this->createMessageParts(['user']);
		$msg['body'] = '';
		$message = $this->createMessage($msg);
		$this->assertNull($message->getSkinny());
	}

	public function testGetTicketIdWhereIdExists()
	{
		$message = m::mock('App\Services\Piper\Message[getSubject]', [$this->parser, $this->user, $this->org]);
		$message->shouldReceive('getSubject')->andReturn('[Activity - #5058] - Email to outside emails');
		$this->assertEquals(5058, $message->getTicketId());
	}

	public function testGetTicketIdWhereIdDoesNotExists()
	{
		$message = m::mock('App\Services\Piper\Message[getSubject]', [$this->parser, $this->user, $this->org]);
		$message->shouldReceive('getSubject')->andReturn('Email to outside emails');
		$this->assertNull($message->getTicketId());
	}

	public function testGetTagsAuthorCannotUseTags()
	{
		$user = $this->createUser();
		$message = m::mock('App\Services\Piper\Message', [$this->parser, $this->user, $this->org])->makePartial();
		// $message->shouldReceive('getAuthor')->andReturn($user);
		$tags = $message->getTags();
		$this->assertInstanceOf('Illuminate\Support\Collection', $tags);
		$this->assertEquals(0, $tags->count());
	}

	public function testGetTagsAuthorNull()
	{
		$user = $this->createUser(['null']);
		$message = m::mock('App\Services\Piper\Message', [$this->parser, $this->user, $this->org])->makePartial();
		$tags = $message->getTags();
		$this->assertInstanceOf('Illuminate\Support\Collection', $tags);
		$this->assertEquals(0, $tags->count());
	}

	public function testGetTags()
	{
		$msg = $this->createMessageParts(['user', 'org', 'assigned']);
		$user = $this->createUser(['is_staff' => true]);
		$message = $this->createMessage($msg);
		$message->shouldReceive('getAuthor')->andReturn($user);
		
		$tags = $message->getTags();
		$this->assertInstanceOf('Illuminate\Support\Collection', $tags);
		$this->assertEquals(3, $tags->count());
		//test $data properties store
		$tagz = $message->getTags();
		$this->assertInstanceOf('Illuminate\Support\Collection', $tagz);
		$this->assertEquals(3, $tagz->count());
	}

	public function testGetAuthor()
	{
		$user = $this->createUser();
		$message = m::mock('App\Services\Piper\Message', [$this->parser, $this->user, $this->org])->makePartial();
		$author = $message->getAuthor();
		$this->assertEquals($user->id, $author->id);
		$this->assertEquals($user->display_name, $author->display_name);
		$this->assertEquals($user->email, $author->email);
		$this->assertEquals($user->id, $message->getAuthorId());
		$auther = $message->getAuthor();
		$this->assertEquals($user->email, $auther->email);
	}

	public function testGetAuthorNull()
	{
		$this->createUser(['null']);
		$message = m::mock('App\Services\Piper\Message', [$this->parser, $this->user, $this->org])->makePartial();
		$author = $message->getAuthor();
		$this->assertNull($author);
		$auther = $message->getAuthor();
		$this->assertNull($auther);
		$this->assertNull($message->getAuthorId());
	}

	public function testGetUser()
	{
		$msg = $this->createMessageParts(['org', 'assigned', 'user']);
		$user = factory(App\User::class)->make(['username' => $msg['tags']['user']]);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findByUserName')->with($msg['tags']['user'])->andReturn($user);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		$return = $message->getUser();
		$this->assertInstanceOf('App\User', $return);
		$this->assertEquals($msg['tags']['user'], $return->username);
		$retern = $message->getUser();
		$this->assertInstanceOf('App\User', $retern);
		$this->assertEquals($msg['tags']['user'], $retern->username);
	}

	public function testGetUserNoUserTag()
	{
		$msg = $this->createMessageParts();
		$author = factory(App\User::class)->make();
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		$return = $message->getUser();
		$this->assertInstanceOf('App\User', $return);
		$this->assertEquals($author->username, $return->username);
	}

	public function testGetUserNull()
	{
		$msg = $this->createMessageParts(['org', 'assigned', 'user']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findByUserName')->with($msg['tags']['user'])->andReturn(null);
		$this->user->shouldReceive('findOrCreate');
		$message = $this->createMessage($msg);
		$return = $message->getUser();
		$this->assertNull($return);
		$this->assertNull($message->getUserId());

	}

	public function testCreatedTicket()
	{
		$ticket = factory(App\Ticket::class)->make(['id' => 5678]);
		$message = m::mock('App\Services\Piper\Message', [$this->parser, $this->user, $this->org])->makePartial();
		$this->assertNull($message->getCreatedTicket());
		$this->assertNull($message->getCreatedTicketId());
		$this->assertTrue($message->setCreatedTicket($ticket));
		$this->assertEquals($ticket, $message->getCreatedTicket());
		$this->assertEquals(5678, $message->getCreatedTicketId());
	}

	public function testGetAssignnedId()
	{
		$msg = $this->createMessageParts(['org', 'assign', 'user']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$assigned = factory(App\User::class)->make(['is_staff' => true, 'id' => 2345]);
		$msg['tags']['assign'] = $assigned->username;
		$this->user->shouldReceive('findByUserName')->with($msg['tags']['assign'])->andReturn($assigned);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		// $id = $message->getAssignedId();
		$this->assertEquals(2345, $message->getAssignedId());
		// $id2 = $message->getAssignedId();
		$this->assertEquals(2345, $message->getAssignedId());
	}

	public function testGetAssignnedIdByClaimTag()
	{
		$msg = $this->createMessageParts(['org', 'claim']);
		$author = factory(App\User::class)->make(['is_staff' => true, 'id' => 9876]);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		$this->assertEquals(9876, $message->getAssignedId());
	}


	public function testGetAssignnedIdNoAssignOrClaimTag()
	{
		$msg = $this->createMessageParts(['org', 'user']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		$this->assertNull($message->getAssignedId());
	}

	public function testGetAssignnedIdAssignNotFound()
	{
		$msg = $this->createMessageParts(['org', 'assign', 'user']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findByUserName')->with($msg['tags']['assign'])->andReturn(null);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		$this->assertNull($message->getAssignedId());
		$this->assertNull($message->getAssignedId());
	}

	public function testGetOrgId()
	{
		$msg = $this->createMessageParts(['org', 'assign', 'user']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$org = factory(App\Org::class)->make();
		$this->org->shouldReceive('findWhere->first')->andReturn($org);
		$message = $this->createMessage($msg);
		$this->assertEquals($org->id, $message->getOrgId());
		$this->assertEquals($org->id, $message->getOrgId());
	}

	public function testGetOrgIdNoOrgTag()
	{
		$msg = $this->createMessageParts(['assign', 'user']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$message = $this->createMessage($msg);
		$this->assertNull($message->getOrgId());
	}

	public function testGetOrgIdOrgNotFound()
	{
		$msg = $this->createMessageParts(['assign', 'user', 'org']);
		$author = factory(App\User::class)->make(['is_staff' => true]);
		$this->user->shouldReceive('findOrCreate')->andReturn($author);
		$this->org->shouldReceive('findWhere->first')->andReturn(null);
		$message = $this->createMessage($msg);
		$this->assertNull($message->getOrgId());
	}

	public function createMessageParts(array $tags = [])
	{
		$faker = Faker::create();
		$array = ['tags' => []];
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				$array['tags'][$tag] = $faker->words(3, true);
			}
		}
		$array['body'] = $faker->paragraph;

		return $array;
	}

	public function createMessage(array $parts)
	{

		$tags = '';
		foreach ($parts['tags'] as $tag => $value) {
			$tags .= '#' . $tag . ' ' . $value . "\n";
		}
		// $tags . "\n" . $parts['body'];

		$message = m::mock('App\Services\Piper\Message[getMessageBody]', [$this->parser, $this->user, $this->org]);
		$message->shouldReceive('getMessageBody')->andReturn($tags . "\n" . $parts['body']);
		return $message;
	}

	public function createUser($params = [])
	{
		$user = isset($params[0]) && $params[0] == 'null' ? null : factory(App\User::class)->make($params);
		$this->user->shouldReceive('findOrCreate')->andReturn($user);
		$this->user->shouldReceive('findByUserName')->andReturn($user);
		return $user;
	}
}