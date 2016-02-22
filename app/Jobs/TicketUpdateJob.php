<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Contracts\Repositories\TicketInterface;
use App\Contracts\Repositories\TicketActionInterface;
use App\Ticket;
use App\TicketAction;
use App\Events\ActionCreatedEvent;

class TicketUpdateJob extends Job implements SelfHandling
{
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $ticket_id,
        $auth_id,
        $user_id = null,
        $org_id = null,
        $priority = null,
        $title = null,
        $body = null,
        $reason = null,
        $source = null,
        $defer_event = false
    ) {
        $this->data = collect([
            'ticket_id' => $ticket_id,
            'auth_id' => $auth_id,
            'user_id' => $user_id,
            'org_id' => $org_id,
            'priority' => $priority,
            'title' => $title,
            'body' => $body,
            'reason' => $reason,
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
    public function handle(TicketInterface $ticket, TicketActionInterface $action)
    {
        $ticketOld = $ticket->find($this->data->get('ticket_id'));
        $actionOld = $action->findWhere(['ticket_id' => $this->data->get('ticket_id'), 'type' => 'create'])->first();
        // dd($this->data);
        $ticket->update($this->data->only(['user_id', 'priority', 'org_id'])->toArray(), $this->data->get('ticket_id'));
        $action->update($this->data->only(['body', 'title'])->toArray(), $actionOld->id);

        $ticketUpdated = $ticket->find($this->data->get('ticket_id'));
        $actionUpdated = $action->find($actionOld->id);

        $changes = array_merge(
            $this->getActionChanges($actionOld, $actionUpdated),
            $this->getTicketChanges($ticketOld, $ticketUpdated)
        );
        
        if (empty($changes)) {
            return null;
        }

        $action = $this->createEditAction($action, $changes);

        $action->ticketOld = $ticketOld;
        $action->actionOld = $actionOld;
        $action->ticketUpdated = $ticketUpdated;

        //throw event
        if (!$this->data->get('defer_event')) {
            event(new ActionCreatedEvent($action));
        }

        return $action;

    }

    public function createEditAction(TicketActionInterface $action, array $changes)
    {
        //build and merge reason into body
        $data = $this->data->merge([
            'type' => 'edit',
            'body' => $this->createBody($changes). "\n" . $this->data->get('reason'),
            'user_id' => $this->data->get('auth_id')
        ]);

        return $action->create($data->only(['type', 'body', 'user_id', 'ticket_id', 'source'])->toArray());
    }

    public function getTicketChanges(Ticket $old, Ticket $updated)
    {
        $changed = [];


        if ($updated->user_id != $old->user_id) {
            $changed['User'] = ['from' => $old->user->display_name, 'to' => $updated->user->display_name];
        }

        if ($updated->priority != $old->priority) {
            $changed['Priority'] = ['from' => $old->priority, 'to' => $updated->priority];
        }

        if ($updated->org_id != $old->org_id) {
            $changed['Organization'] = ['from' => $old->org->name, 'to' => $updated->org->name];
        }

        return $changed;
    }

    public function getActionChanges(TicketAction $old, TicketAction $action)
    {
        $changed = [];

        if ($action->title != $old->title) {
            $changed['Title'] = ['from' => $action->title, 'to' => $old->title];
        }

        if ($action->body != $old->body) {
            $changed['Body'] = ['from' => $action->body, 'to' => $old->body];
        }

        return $changed;
    }

    public function createBody($changes)
    {
        $body = null;
        foreach ($changes as $attr => $change) {
            $body.= trans(
                'action.changedfrom',
                ['attr' => trans('ticket.' . $attr), 'from' => $change['from'], 'to' => $change['to']]
            ) . "\n";
        }
        return $body;
    }
}
