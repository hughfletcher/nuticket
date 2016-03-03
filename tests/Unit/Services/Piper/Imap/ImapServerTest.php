<?php

use Tests\TestCase, Mockery as m;
use App\Services\Piper\Imap\ImapServer;

class ImapServerTest extends TestCase {

	public function testNewServer()
	{
		$email = factory(App\Email::class)->make();
		$server = new ImapServer($email, $this->app);
		$this->assertInstanceOf('Fetch\Server', $server->newServer('mail.dummy.com'));
	}

	public function testMessages()
	{
		$email = factory(App\Email::class)->make(['mail_ssl' => true]);
		$messages = array_fill(0, 3, m::mock('Fetch\Message'));

		$server = m::mock('Fetch\Server[getMessages]', [$email->mail_host]);
		$server->shouldReceive('getMessages')->once()->andReturn($messages);

		$imap = m::mock('App\Services\Piper\Imap\ImapServer[newServer]', [$email, $this->app]);
		$imap->shouldReceive('newServer')->once()->andReturn($server);
		
		$return = $imap->messages();
		$this->assertEquals(3, $return->count());

	}

	public function testMessagesThrowException()
	{
		$email = factory(App\Email::class)->make(['mail_ssl' => true]);

		$server = m::mock('Fetch\Server[getMessages]', [$email->mail_host]);
		$server->shouldReceive('getMessages')->once()->andThrow('ErrorException', 'Can not connect to imap server.');

		Log::shouldReceive('notice');
		$imap = m::mock('App\Services\Piper\Imap\ImapServer[newServer]', [$email, $this->app]);
		$imap->shouldReceive('newServer')->once()->andReturn($server);
		try {
			$return = $imap->messages();
			$this->assertEquals(0, $return->count());
		} catch (ErrorException $e) {
			$this->assertEquals('Can not connect to imap server.', $e->getMessage());
		}

	}

}
