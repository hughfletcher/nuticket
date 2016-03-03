<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use ReflectionClass;
use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\TicketActionInterface;
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
        $assigned_id = null,
        $priority = null,
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

        $ticket = $this->createTicket($ticket);

        $this->data->put('ticket_id', $ticket->id);

        $this->createAction($this->data, $action);

        if (!$this->data->get('defer_event')) {
            event(new TicketCreatedEvent($ticket));
        }

        return $ticket;

    }

    private function createTicket(TicketInterface $ticket)
    {
        $ticket = $ticket->job_create($this->data->only(['user_id', 'assigned_id', 'priority', 'dept_id', 'org_id', 'status'])->toArray());

        return $ticket;
    }

    private function createAction(Collection $data, TicketActionInterface $action)
    {
        $data = $data->merge(['user_id' => $data->get('auth_id'), 'type' => 'create']);
        return $action->create($data->only(['ticket_id', 'user_id', 'type', 'title', 'body', 'source'])->toArray());
    }



}
