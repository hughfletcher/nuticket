<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
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

    public function storeRules()
    {
        return [
            'user_id' => ['required_without_all:display_name,email', 'exists:users,id'],
            'display_name' => ['required_without:user_id'],
            'email' => ['required_without:user_id', 'email'],
            'priority' => ['between:1,5'],
            'title' => ['required', 'min:10'],
            'body' => ['required', 'min:10'],
            'hours' => ['numeric'],
            'time_at' => ['required_with:hours', 'date_format:m/d/Y'],
            'reply' => ['min:3'],
            'comment' => ['min:3'],
            'status' => ['in:open,closed,resolved,new'],
            'dept_id' => ['exists:depts,id'],
            'assigned_id' => ['exists:users,id,is_staff,1'],
        ];
    }

    public function updateRules()
    {
        return [
            'user_id' => ['exists:users,id'],
            'priority' => ['between:1,5'],
            'title' => ['min:10'],
            'body' => ['min:10'],
            'reason' => ['min:5']
        ];
    }
}
