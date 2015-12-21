Hi {{ $user->first_name }},

Summary: {!! $ticket->title !!}
User: {{ $ticket->user->display_name }}
Dept: {{ $ticket->dept->name }}
Assigned: {{ $ticket->assigned_id > 0 ? $ticket->assigned->display_name : 'Nobody' }}

@each('mail.common.action', $ticket->actions, 'action')
