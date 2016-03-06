<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\TicketCreatedEvent' => ['App\Listeners\NotifyTicketCreatedListener'],
		'App\Events\ActionCreatedEvent' => ['App\Listeners\NotifyTicketActivityListener'],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		$events->listen('mailer.sending', function ($message) {
        	debug('Email sent "' . $message->getSubject() . '" to "' . array_keys($message->getTo())[0] . '".', ['message' => $message->toString()]);
    	});
	}

}
