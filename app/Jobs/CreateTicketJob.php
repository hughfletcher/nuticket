<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use ReflectionClass;
use App\Contracts\Repositories\UserInterface;
use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\TicketActionInterface;
use App\Contracts\Repositories\TimeLogInterface;
use Carbon\Carbon;
use App\Events\TicketCreatedEvent;

class CreateTicketJob extends Job implements SelfHandling
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
        $source,
        $status = 'new',
        $dept_id = null,
        $assigned_id = 0,
        $hours = null,
        Carbon $time_at = null,
        $priority = 3,
        $reply = null,
        $comment = null,
        $display_name = null,
        $email = null
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
    public function handle(UserInterface $user, TicketInterface $ticket, TicketActionInterface $action, TimeLogInterface $time)
    {
        if (!$this->data->has('user_id')) {
            $this->createUser($user);
        }

        $ticket = $this->CreateTicket($ticket);
        $this->createCreateAction($action);

        if ($this->data->has('reply')) {
            $this->createReplyAction($action);
        }

        if ($this->data->has('comment')) {
            $this->createCommentAction($action);
        }

        if ($this->data->has('hours') && $this->data->get('hours') > 0) {
            $this->createTime($time);
        }

        event(new TicketCreatedEvent($ticket));

    }

    public function createUser(UserInterface $user)
    {
        $id = $user->create([
            'display_name' => $this->data->get('display_name'),
            'email' => $this->data->get('email'),
            'username' =>  preg_replace(
                "/[^a-zA-Z0-9]/",
                "",
                $this->data->get('display_name')
            ) . rand(10000, 99990)
        ]);
        $this->data->put('user_id', $id);
    }

    public function CreateTicket(TicketInterface $ticket)
    {
        $this->resolveStatus();

        $ticket = $ticket->job_create(array_only($this->data->toArray(), ['user_id', 'assigned_id', 'priority', 'dept_id', 'hours', 'last_action_at', 'status']));
        $this->data->put('ticket_id', $ticket->id);
        return $ticket;
    }

    public function createCreateAction(TicketActionInterface $action)
    {
        $data = $this->data->merge(['user_id' => $this->data->get('auth_id'), 'type' => 'create']);
        return $action->create(array_only($data->toArray(), ['ticket_id', 'user_id', 'type', 'title', 'body', 'source']));
    }

    public function createReplyAction(TicketActionInterface $action)
    {
        $type = in_array($this->data->get('status'), ['closed', 'resolved']) ? $this->data->get('status') : 'reply';
        $data = $this->data->merge(['user_id' => $this->data->get('auth_id'), 'type' => $type]);
        $action = $action->create(array_only($data->toArray(), ['ticket_id', 'user_id', 'type', 'body', 'source']));
        $this->data->put('ticket_action_id', $action->id);
        return $action;
    }

    public function createCommentAction(TicketActionInterface $action)
    {
        $data = $this->data->merge(['user_id' => $this->data->get('auth_id'), 'type' => 'comment']);
        $action = $action->create(array_only($data->toArray(), ['ticket_id', 'user_id', 'type', 'body', 'source']));
        // if comment only then set $ticket_action_id
        if (!$this->data->has('ticket_action_id')) { $this->data->put('ticket_action_id', $action->id); }
        return $action;
    }

    public function createTime(TimeLogInterface $time)
    {
        $data = $this->data->merge(['user_id' => $this->data->get('auth_id'), 'type' => 'action']);
        return $time->create(array_only($data->toArray(), ['user_id', 'hours', 'ticket_action_id', 'type', 'time_at']));
    }

    public function resolveStatus()
    {
        if ($this->data->has('reply') || $this->data->has('comment')) {
            $this->data->put('last_action_at', Carbon::now());

            if (in_array($this->data->get('status'), ['closed', 'resolved'])) {
                $this->data->put('closed_at', Carbon::now());
            } else {
                $this->data->put('status', 'open');
            }
        }
    }

}
