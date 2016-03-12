<?php 

use Tests\TestCase;
use Mockery as m;
use App\Exceptions\Handler;

class HandlerTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->user = m::mock('App\Contracts\Repositories\UserInterface');
		$this->mailer = m::mock('Illuminate\Mail\Mailer');
		$this->logger = m::mock('Psr\Log\LoggerInterface');
	}

	public function testReport()
	{
		$admins = factory(App\User::class, 10)->make(['is_admin' => true]);
		
		$this->user->shouldReceive('findAllBy')->andReturn($admins);
		$e = m::mock('Exception');
		$e->shouldReceive('getMessage');
		$e->shouldReceive('getFile');
		$e->shouldReceive('getLine');
		
		$this->mailer->shouldReceive('raw')->with(m::any(), m::on(function($closure) use ($admins) {
			$message = m::mock('Illuminate\Mailer\Message');
	        $message->shouldReceive('to')
	            ->with($admins->lists('display_name', 'email')->toArray())
	            ->andReturn(m::self());
	        $message->shouldReceive('from')
	            ->with(config('settings.mail.admin'))
	            ->andReturn(m::self());
	        $message->shouldReceive('subject');
	        $closure($message);
			return true;
		}));
		
		$this->logger->shouldReceive('error');

		$handler = new Handler($this->logger, $this->mailer, $this->user);
		$handler->report($e);

	}

	public function testRender()
	{
		$request = m::mock();
		$e = m::mock('Exception');
		$handler = new Handler($this->logger, $this->mailer, $this->user);
		$this->assertInstanceOf('Illuminate\Http\Response', $handler->render($request, $e));
	}

	public function testRenderTokenMismatch()
	{
		$request = m::mock();
		$e = m::mock('Illuminate\Session\TokenMismatchException');
		$handler = new Handler($this->logger, $this->mailer, $this->user);
		$this->assertInstanceOf('Illuminate\Http\RedirectResponse', $handler->render($request, $e));
		$this->assertSessionHas('message', ['type' => 'warning', 'body' => trans('common.csrf_error')]);
	}
}