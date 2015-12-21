<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TicketActionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function createRules()
    {
        return [
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'ticket_id' => ['required', 'numeric', 'exists:tickets,id'],
            'body' => ['required', 'min:3'],
            'status' => ['in:closed,open,resolved'],
            'hours' => ['numeric'],
            'transfer_id' => ['required_if:type,transfer', 'numeric', 'exists:depts,id'],
            'assigned_id' => ['required_if:type,assign', 'numeric'],
            'time_at' => ['date_format:m/d/Y'],
        ];
    }
}
