{{ trans('mail.hi_user', ['name' => $user['first_name']], $user['locale']) }},

{!! trans('mail.def.summary', ['summary' => $ticket['title']], $user['locale']) !!}
{!! trans('mail.def.user', ['user' => $ticket['user']['display_name']], $user['locale']) !!}
{!! trans('mail.def.org', ['org' => $ticket['org']['name']], $user['locale']) !!}
{!! trans('mail.def.dept', ['dept' => $ticket['dept']['name']], $user['locale']) !!}
{!! trans('mail.def.assigned', ['assigned' => ($ticket['assigned_id'] > 0 ? $ticket['assigned']['display_name'] : 'Nobody')], $user['locale']) !!}

@include('mail.common.action')
