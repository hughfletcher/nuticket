<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Contracts\Repositories\TicketActionInterface;
use App\Contracts\Repositories\TimeLogInterface;
use App\Contracts\Repositories\TicketInterface;
use App\TicketAction;
use App\Ticket;
use Carbon\Carbon;
use App\Events\ActionCreatedEvent;

class ActionCreateJob extends Job implements SelfHandling
{
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $ticket_id,
        $user_id,
        $type,
        $body,
        $transfer_id = null,
        $assigned_id = null,
        $hours = null,
        $time_at = null,
        $source = null,
        $defer_event = null
    )
    {
        $this->data = collect([
            'ticket_id' => $ticket_id,
            'user_id' => $user_id,
            'type' => $type,
            'body' => $body,
            'transfer_id' => $transfer_id,
            'assigned_id' => $assigned_id,
            'hours' => $hours,
            'time_at' => $time_at,
            'source' => $source,
            'defer_event' => $defer_event
        ])->filter(function ($item) {
            return $item != null;
        });


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TicketActionInterface $action, TimeLogInterface $time, TicketInterface $ticket)
    {
        $exist = $ticket->find($this->data->get('ticket_id'));

        if ($exist->status == $this->data->get('type')) {
            throw Exception('Action can not be ' . $this->data->get('type') . ' on an existing ' . $exist->status . ' ticket.');
        }

        $action = $action->create($this->data->except(['hours', 'time_at', 'defer_event'])->toArray());

        //update timelog
        if ($this->data->get('hours') > 0 && in_array($this->data->get('type'), ['reply', 'comment', 'closed', 'resolved', 'open'])) {
            $this->updateTimeLog($action, $time);
        }

        // update ticket
        $ticket->update($this->getTicketAttrs($exist), $exist->id);

        //throw event
        if (!$this->data->get('defer_event')) {
            event(new ActionCreatedEvent($action));
        }

        return $action;
    }

    public function updateTimeLog(TicketAction $action, TimeLogInterface $time)
    {
        return $time->create([
            'user_id' => $action->user_id,
            'hours' => $this->data->get('hours'),
            'type' => 'action',
            'ticket_action_id' => $action->id,
            'time_at' => $this->data->has('time_at') ? $this->data->get('time_at') : Carbon::now()
        ]);
    }

    private function getTicketAttrs(Ticket $ticket)
    {
        return array_merge(
            ['last_action_at' => Carbon::now()],
            $this->getStatus(),
            $this->getHours($ticket->hours),
            $this->getDept(),
            $this->getAssigned()
        );
    }

    private function getAssigned()
    {
        if ($this->data->get('type') != 'assign') {
            return [];
        }

        return ['assigned_id' => $this->data->get('assigned_id')];
    }

    private function getDept()
    {
        if ($this->data->get('type') != 'transfer') {
            return [];
        }

        return ['dept_id' => $this->data->get('transfer_id')];
    }

    private function getStatus()
    {
        $type = $this->data->get('type');

        if (!in_array($type, ['open', 'closed', 'resolved'])) {
            return [];
        }

        if ($type == 'open') {
            return ['status' => 'open', 'closed_at' => null];
        }

        return ['status' => $type, 'closed_at' => Carbon::now()];
    }

    private function getHours($hours)
    {
        if (!$this->data->has('hours') || !in_array($this->data->get('type'), ['reply', 'closed', 'resolved', 'comment', 'open'])) {
            return [];
        }

        return ['hours' => $hours + $this->data->get('hours')];
    }
}
