<?php namespace App\Repositories\Eloquent;

use App\Repositories\TicketActionInterface;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;
use App\Ticket;
use App\TimeLog;
use Carbon\Carbon;

class TicketActionRepository extends Repository implements TicketActionInterface {

    public function model() {
        return 'App\TicketAction';
    }

    /**
     * Create Action and update ticket
     *
     * @param  array $attrs [ticket_id, user_id, type, body, [title, assigned_id, transfer_id, hours, status]]
     * @return App\TicketAction
     */
    public function create(array $data)
    {
        // create action
        $action = parent::create(array_except($data, ['hours', 'time_at', 'status']));

        //update timelog
        if (isset($data['hours']) && $data['hours'] > 0)
        {
            $this->updateTimeLog($action->id, $action->user_id, $data['hours'], $data['time_at']);
        }

        // update ticket
        $ticket = $this->updateTicket($data);

        if (isset($ticket['old_status']) && $ticket['old_status'] != $ticket['status']) {

            $action->type = $ticket['status'];

    	}

        $action->save();

        return $action;

	}

    protected function updateTimeLog($action_id, $user_id, $hours, $time_at)
    {
        return $this->createTimeLogModel()->create([
            'user_id' => $user_id,
            'hours' => $hours,
            'type' => 'action',
            'ticket_action_id' => $action_id,
            'time_at' => Carbon::createFromFormat('m/d/Y', $time_at)
        ]);
    }

        /**
     * Update ticket by a reply ticket action
     *
     * @param  array App\TicketAction + $status
     * @return array App\Ticket + $old_status
     */
    protected function updateTicket(array $data)
    {
        $ticket = $this->createTicketModel()->find($data['ticket_id']);
        // $ticket = $ticket;

        $old_status = $ticket->status != 'new' ? $ticket->status : null;

        $ticket->last_action_at = Carbon::now();


        if (in_array($data['type'], ['reply', 'closed', 'resolved', 'comment']))
        {
            $ticket->hours += $data['hours'];
        }

        if (in_array($data['type'], ['reply', 'closed', 'resolved']))
        {
            $ticket->status = isset($data['status']) ? $data['status'] : $ticket->status;
            $ticket->closed_at = isset($data['status']) && in_array($data['status'], ['resolved', 'closed']) ? Carbon::now() : $ticket->closed_at;
        }

        if ($data['type'] == 'transfer')
        {
            $ticket->dept_id = $data['transfer_id'];
        }

        if ($data['type'] == 'assign')
        {
            $ticket->assigned_id = $data['assigned_id'];
        }

        $ticket->save();
        // $ticket->put('old_status', $old_status);

        return array_add($ticket->toArray(), 'old_status', $old_status);
    }

    public function createTicketModel()
    {
        return new Ticket;
    }

    public function createTimeLogModel()
    {
        return new TimeLog;
    }


}
