<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use ReflectionClass;
// use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\TicketActionInterface;
// use App\Contracts\Repositories\TimeLogInterface;
// use Carbon\Carbon;
use App\Events\TicketCreatedEvent;
use Illuminate\Support\Collection;

class TicketCreateJob extends Job implements SelfHandling
{
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $auth_id,
        $user_id,
        $title,
        $body,
        $source = null,
        $status = 'new',
        $dept_id = null,
        $org_id = null,
        $assigned_id = 0,
        $priority = 3,
        $defer_event = false
    )
    {
        $parameters = (new ReflectionClass(__CLASS__))->getMethod(__FUNCTION__)->getParameters();

        $this->data = collect();
        foreach($parameters as $parameter)
        {
            if (${$parameter->name}) {
                $this->data->put($parameter->name, ${$parameter->name});
            }
        }

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TicketInterface $ticket, TicketActionInterface $action)
    {

        $ticket = $this->createTicket($this->data, $ticket);

        $this->data->put('ticket_id', $ticket->id);

        $this->createAction($this->data, $action);

        if (!$this->data->get('defer_event')) {
            event(new TicketCreatedEvent($ticket));
        }


        return $ticket;

    }

    public function createTicket(Collection $data, TicketInterface $ticket)
    {

        $ticket = $ticket->job_create($data->only(['user_id', 'assigned_id', 'priority', 'dept_id', 'org_id', 'hours', 'last_action_at', 'status'])->toArray());

        return $ticket;
    }

    public function createAction(Collection $data, TicketActionInterface $action)
    {
        $data = $data->merge(['user_id' => $data->get('auth_id'), 'type' => 'create']);
        return $action->create($data->only(['ticket_id', 'user_id', 'type', 'title', 'body', 'source'])->toArray());
    }

}
