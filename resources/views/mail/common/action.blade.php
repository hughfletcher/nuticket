{{ date_format($action->created_at, config('system.format.datetime'))}} - {{ $action->user->display_name }}@if($action->type == 'assign') {{ trans('action.acted_on.assign', ['name' => $action->assigned->display_name]) }}
@elseif ($action->type == 'transfer') {{ trans('action.acted_on.transfer', ['name' => $action->transfer->display_name]) }}
@else {{ trans('action.acted_on.' . $action->type) }}
@endif
{!! $action->body !!}

 
