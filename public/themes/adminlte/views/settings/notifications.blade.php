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
    {{-- {{ dd($errors)}} --}}
    <div class="box box-primary">
        <form method="POST" action="{{ route('settings.update', ['notifications']) }}" accept-charset="UTF-8" class="form-horizontal" id="create-form">
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="box-header">
    			<h3 class="box-title">{{ trans('settings.new_ticket_notification') }}</h3>
    		</div>
            <div class="box-body">
                <div class="form-group">
                    <label for="settings_notify_newticket_status" class="col-sm-2 control-label">{{ trans('settings.status') }}</label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_notify_newticket_status" value="1"{{ old('settings_notify_newticket_status', config('settings.notify.newticket.status')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_notify_newticket_status" value="0"{{ old('settings_notify_newticket_status', config('settings.notify.newticket.status')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_newticket_admin" class="col-sm-2 control-label">{{ trans('settings.admin_email') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_newticket_admin" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_newticket_admin" value="1"{{ old('settings_notify_newticket_admin', config('settings.notify.newticket.admin')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
				<div class="form-group">
					<label for="settings_notify_newticket_mgr" class="col-sm-2 control-label">{{ trans('settings.department_manager') }}</label>
					<div class="col-sm-2">
						{{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_newticket_mgr" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_newticket_mgr" value="1"{{ old('settings_notify_newticket_mgr', config('settings.notify.newticket.mgr')) ? ' checked' : null }}>
                          </label>
                        </div>
					</div>
				</div>
                <div class="form-group">
                    <label for="settings_notify_newticket_dept" class="col-sm-2 control-label">{{ trans('settings.department_members') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_newticket_dept" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_newticket_dept" value="1"{{ old('settings_notify_newticket_dept', config('settings.notify.newticket.dept')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_newticket_org" class="col-sm-2 control-label">{{ trans('settings.organization_manager') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_newticket_org" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_newticket_org" value="1"{{ old('settings_notify_newticket_org', config('settings.notify.newticket.org')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
    		</div>
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.new_ticket_reply_notification') }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="settings_notify_reply_status" class="col-sm-2 control-label">{{ trans('settings.status') }}</label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_notify_reply_status" value="1"{{ old('settings_notify_reply_status', config('settings.notify.reply.status')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_notify_reply_status" value="0"{{ old('settings_notify_reply_status', config('settings.notify.reply.status')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_reply_last" class="col-sm-2 control-label">{{ trans('settings.last_respondent') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_reply_last" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_reply_last" value="1"{{ old('settings_notify_reply_last', config('settings.notify.reply.last')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_reply_assigned" class="col-sm-2 control-label">{{ trans('settings.assigned_user') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_reply_assigned" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_reply_assigned" value="1"{{ old('settings_notify_reply_assigned', config('settings.notify.reply.assigned')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_reply_mgr" class="col-sm-2 control-label">{{ trans('settings.department_manager') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_reply_mgr" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_reply_mgr" value="1"{{ old('settings_notify_reply_mgr', config('settings.notify.reply.mgr')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_reply_org" class="col-sm-2 control-label">{{ trans('settings.organization_manager') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_reply_org" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_reply_org" value="1"{{ old('settings_notify_reply_org', config('settings.notify.reply.org')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.new_internal_activity_notification') }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="settings_notify_internal_status" class="col-sm-2 control-label">{{ trans('settings.status') }}</label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_notify_internal_status" value="1"{{ old('settings_notify_internal_status', config('settings.notify.internal.status')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_notify_internal_status" value="0"{{ old('settings_notify_internal_status', config('settings.notify.internal.status')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_internal_last" class="col-sm-2 control-label">{{ trans('settings.last_respondent') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_internal_last" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_internal_last" value="1"{{ old('settings_notify_internal_last', config('settings.notify.internal.last')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_internal_assigned" class="col-sm-2 control-label">{{ trans('settings.assigned_user') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_internal_assigned" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_internal_assigned" value="1"{{ old('settings_notify_internal_assigned', config('settings.notify.internal.assigned')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_internal_mgr" class="col-sm-2 control-label">{{ trans('settings.department_manager') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_internal_mgr" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_internal_mgr" value="1"{{ old('settings_notify_internal_mgr', config('settings.notify.internal.mgr')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.ticket_assignment_notification') }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="settings_notify_assign_status" class="col-sm-2 control-label">{{ trans('settings.status') }}</label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_notify_assign_status" value="1"{{ old('settings_notify_assign_status', config('settings.notify.assign.status')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_notify_assign_status" value="0"{{ old('settings_notify_assign_status', config('settings.notify.assign.status')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_assign_assigned" class="col-sm-2 control-label">{{ trans('settings.assigned_user') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_assign_assigned" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_assign_assigned" value="1"{{ old('settings_notify_assign_assigned', config('settings.notify.assign.assigned')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_assign_mgr" class="col-sm-2 control-label">{{ trans('settings.department_manager') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_assign_mgr" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_assign_mgr" value="1"{{ old('settings_notify_assign_mgr', config('settings.notify.assign.mgr')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_assign_dept" class="col-sm-2 control-label">{{ trans('settings.department_members') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_assign_dept" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_assign_dept" value="1"{{ old('settings_notify_assign_dept', config('settings.notify.assign.dept')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.ticket_transfer_notification') }}</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="settings_notify_transfer_status" class="col-sm-2 control-label">{{ trans('settings.status') }}</label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_notify_transfer_status" value="1"{{ old('settings_notify_transfer_status', config('settings.notify.transfer.status')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_notify_transfer_status" value="0"{{ old('settings_notify_transfer_status', config('settings.notify.transfer.status')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_transfer_assigned" class="col-sm-2 control-label">{{ trans('settings.assigned_user') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_transfer_assigned" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_transfer_assigned" value="1"{{ old('settings_notify_transfer_assigned', config('settings.notify.transfer.assigned')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_transfer_mgr" class="col-sm-2 control-label">{{ trans('settings.department_manager') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_transfer_mgr" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_transfer_mgr" value="1"{{ old('settings_notify_transfer_mgr', config('settings.notify.transfer.mgr')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_notify_transfer_dept" class="col-sm-2 control-label">{{ trans('settings.department_members') }}</label>
                    <div class="col-sm-2">
                        {{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                        <div class="checkbox show">
                          <label>
                            <input name="settings_notify_transfer_dept" type="hidden" value="0">
                            <input type="checkbox" name="settings_notify_transfer_dept" value="1"{{ old('settings_notify_transfer_dept', config('settings.notify.transfer.dept')) ? ' checked' : null }}>
                          </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-header">
                <h3 class="box-title">{{ trans('settings.system_notifications') }}</h3>
            </div>
            <div class="box-body">
				<div class="form-group">
                    <label for="settings_notify_system_status" class="col-sm-2 control-label">{{ trans('settings.status') }}</label>
                    <div class="col-sm-10">
                          <label class="radio-inline">
                              <input type="radio" name="settings_notify_system_status" value="1"{{ old('settings_notify_system_status', config('settings.notify.system.status')) == true ? ' checked' : null }}> {{ trans('settings.enabled') }}
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="settings_notify_system_status" value="0"{{ old('settings_notify_system_status', config('settings.notify.system.status')) == false ? ' checked' : null }}> {{ trans('settings.disabled') }}
                            </label>
                    </div>
                </div>
            <div class="box-footer">
    			<button class="btn btn-primary">{{ trans('common.update') }}</button>
    		</div>


		<!-- right column -->
        </form>
	</div>
</section>

@stop
