@foreach ($ticket['actions'] as $action)
{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $action['created_at'])->tz($user['timezone'])->format(config('system.format.datetime')) }} - {{ $action['user']['display_name'] }}@if($action['type'] == 'assign') {{ trans('action.acted_on.assign', ['name' => $action['assigned']['display_name']], $user['locale']) }}
@elseif ($action['type'] == 'transfer') {{ trans('action.acted_on.transfer', ['name' => $action['transfer']['name']], $user['locale']) }}
@else {{ trans('action.acted_on.' . $action['type']) }}
@endif
{!! $action['body'] !!}

@endforeach