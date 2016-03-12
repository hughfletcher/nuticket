<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Psr\Log\LoggerInterface;
use Illuminate\Mail\Mailer;
use App\Contracts\Repositories\UserInterface;

class Handler extends ExceptionHandler {

	public function __construct(LoggerInterface $log, Mailer $mailer, UserInterface $user)
    {
    	parent::__construct($log);
        $this->mailer = $mailer;
        $this->user = $user;
    }
	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		if (app()->environment('testing', 'production') && config('settings.notify.system.admin')) {

            $admins = $this->user->findAllBy('is_admin', true)->lists('display_name', 'email');
            $body = $e->getMessage() . ' in file "' . $e->getFile() . '" on line ' . $e->getLine() . '.';
            $this->mailer->raw($body, function ($message) use ($admins) {
	                $message->from(config('settings.mail.admin'))
	                    ->to($admins->toArray())
	                    ->subject('[Log] An Error Occured!');
	            }
	        );
        }

		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if ($e instanceof TokenMismatchException){

            return back()->with('message',['type' => 'warning', 'body' => trans('common.csrf_error')])->withInput();
        }
		return parent::render($request, $e);
	}

}
