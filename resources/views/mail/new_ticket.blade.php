Hello {{ $user->first_name }},

New ticket #{{ $ticket->id }} has been created.

User: {{ $ticket->user->display_name }}
Dept: {{ $ticket->dept->name }}

{{ $ticket->actions[0]->title }}

{{ $ticket->actions[0]->body }}
