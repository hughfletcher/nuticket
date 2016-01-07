<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Contracts\Repositories\EmailInterface;
use App\Jobs\FetchEmailJob;

class Kernel extends ConsoleKernel {

    use DispatchesJobs;
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\FetchEmail',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
        if (!$this->app->environment('production') || !$this->app['db']->connection()->getSchemaBuilder()->hasTable('emails')) {return;}

        $mail = $this->app->make('App\Contracts\Repositories\EmailInterface');
        $emails = $mail->findAllBy('mail_active', true);

        foreach ($emails as $email) {
            $schedule->call(function() use ($email) {
                $this->dispatch(new FetchEmailJob($email));
            })->cron('*/' . $email->mail_fetchfreq . ' * * * *');
        }

	}

}
