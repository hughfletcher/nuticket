<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\Repositories\EmailInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\FetchEmailJob;

class FetchEmail extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch emails and convert to tickets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EmailInterface $emails)
    {
        parent::__construct();

        $this->emails = $emails;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if ($this->argument('id')) {
            $emails = [$this->emails->find($this->argument('id'))];
        } else {
            $emails = $this->emails->findAllBy('mail_active', 1);
        }

        foreach ($emails as $email) {
            $this->dispatch(new FetchEmailJob($email));
             $this->info('Fetching email job for account ' . $email->name . ' dispatched to queue.');
        }
    }
}
