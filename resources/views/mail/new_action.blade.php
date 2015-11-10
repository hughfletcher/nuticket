Hi {{ $user->first_name }},

Summary: {{ $action->ticket->actions[0]->title }}
User: {{ $action->ticket->user->display_name }}
Dept: {{ $action->ticket->dept->name }}
Assigned: {{ $action->ticket->assigned_id > 0 ? $action->ticket->assigned->display_name : 'Nobody' }}
Status: {{ $action->ticket->status }}

@include('mail.common.action')
