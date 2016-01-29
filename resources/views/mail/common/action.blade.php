{{ $action->created_at->tz('US/Central')->format(config('system.format.datetime')) }} - {{ $action->user->display_name }}@if($action->type == 'assign') {{ trans('action.acted_on.assign', ['name' => $action->assigned->display_name]) }}
@elseif ($action->type == 'transfer') {{ trans('action.acted_on.transfer', ['name' => $action->transfer->name]) }}
@else {{ trans('action.acted_on.' . $action->type) }}
@endif
{!! $action->body !!}
