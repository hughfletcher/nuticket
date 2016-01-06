<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use App\Services\Piper\Piper;
use Fetch\Server;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketUpdateRequest;
use App\Http\Requests\ActionCreateRequest;
use Validator;
use Log;
use Fetch\Message;
use App\Services\EmailParser;
use App\Contracts\Repositories\UserInterface;
use Illuminate\Support\Collection;
use App\Events\TicketCreatedEvent;
use App\Events\ActionCreatedEvent;

class FetchEmailJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle(Piper $piper)
    {
        $piper->fetch($this->email);
    }
}
