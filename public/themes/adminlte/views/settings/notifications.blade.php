@extends('layouts.master')

@section('title', trans('settings.emails'))

@section('content')
<section class="content-header">
	<h1>
		{{ trans('settings.notifications') }}
		<small>{{ trans('settings.settings') }}</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content ticket-create">
    @if (session('message'))
    @include('common.message')
	@endif
    <div class="box box-primary">
        <form method="POST" action="{{ route('settings.update', ['notifications']) }}" accept-charset="UTF-8" class="form-horizontal" id="create-form">
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.autoresponder_settings') }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="settings_notify_internal_status" class="col-sm-2 control-label">{{ trans('settings.by_mail_confirmation') }}  <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.bymail') }}"></span></label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_autorespond_bymail" value="1"{{ old('settings_autorespond_bymail', config('settings.autorespond.bymail')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_autorespond_bymail" value="0"{{ old('settings_autorespond_bymail', config('settings.autorespond.bymail')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
            </div>
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.alert_settings') }}</h3>
            </div>
            <div class="box-body">
                @foreach(config('settings.notify') as $type => $array)
                    @foreach($array as $key => $value)
                    <input name="settings_notify_{{ $type }}_{{ $key }}" type="hidden" value="0">
                    @endforeach
                @endforeach
                <table class="table table-hover">
                    <thead>
                        <tr>    
                            <th> </th>
                            <th>{{ trans('settings.admin') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.admin') }}"></span></th>
                            <th>{{ trans('settings.dept_mgr') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.mgr') }}"></span></th>
                            <th>{{ trans('settings.dept') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.dept') }}"></span></th>
                            <th>{{ trans('settings.org_mgr') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.org') }}"></span></th>
                            <th>{{ trans('settings.assigned') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.assigned') }}"></span></th>
                            <th>{{ trans('settings.last') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.last') }}"></span></th>
                            <th>{{ trans('settings.owner') }} <span class="glyphicon glyphicon-question-sign text-muted" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('settings.help.owner') }}"></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(config('settings.notify') as $key => $value)
                        <tr>
                            <td>@if($key == 'create') {{ trans('settings.new_ticket') }} @elseif($key == 'system') {{ trans('settings.system_events') }}  @else {{ trans('settings.new_' . $key) }} @endif</td>
                            <td>
                                @if(isset($value['admin']))
                                <input type="checkbox" name="settings_notify_{{ $key }}_admin" value="1"{{ old('settings_notify_' . $key . '_admin', config('settings.notify.' . $key . '.admin')) ? ' checked' : null }}>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['mgr']))
                                <input type="checkbox" name="settings_notify_{{ $key }}_mgr" value="1"{{ old('settings_notify_' . $key . '_mgr', config('settings.notify.' . $key . '.mgr')) ? ' checked' : null }}>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['dept']))
                                <input type="checkbox" name="settings_notify_{{ $key }}_dept" value="1"{{ old('settings_notify_' . $key . '_dept', config('settings.notify.' . $key . '.dept')) ? ' checked' : null }}>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['org']))
                                <input type="checkbox" name="settings_notify_{{ $key }}_org" value="1"{{ old('settings_notify_' . $key . '_org', config('settings.notify.' . $key . '.org')) ? ' checked' : null }}>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['assigned']))
                                <input type="checkbox" name="settings_notify_{{ $key }}_assigned" value="1"{{ old('settings_notify_' . $key . '_assigned', config('settings.notify.' . $key . '.assigned')) ? ' checked' : null }}>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['last']))
                                <input type="checkbox" name="settings_notify_{{ $key }}_last" value="1"{{ old('settings_notify_' . $key . '_last', config('settings.notify.' . $key . '.last')) ? ' checked' : null }}>
                                @endif
                            </td>
                            <td>
                                @if(isset($value['owner']))
                                <input type="checkbox" name="settings_notify_c{{ $key }}_owner" value="1"{{ old('settings_notify_' . $key . '_owner', config('settings.notify.' . $key . '.owner')) ? ' checked' : null }}>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
    		</div>
            <div class="box-footer">
    			<button class="btn btn-primary">{{ trans('common.update') }}</button>
    		</div>


		<!-- right column -->
        </form>
	</div>
</section>

@stop
