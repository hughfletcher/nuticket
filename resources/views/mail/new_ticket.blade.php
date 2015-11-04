Hi {{ $user->first_name }},

Ticket #{{ $ticket->id }} has been created.

Summary: {{ $ticket->actions[0]->title }}
User: {{ $ticket->user->display_name }}
Dept: {{ $ticket->dept->name }}
Assigned: {{ $ticket->assigned_id > 0 ? $ticket->assigned->display_name : 'Nobody' }}

@each('mail.common.action', $ticket->actions, 'action')
