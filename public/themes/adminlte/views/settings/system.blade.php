@extends('layouts.master')

@section('title', trans('settings.system'))

@section('content')
<section class="content-header">
	<h1>
		{{ trans('settings.system') }}
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
        <form method="POST" action="{{ route('settings.update', ['system']) }}" accept-charset="UTF-8" class="form-horizontal" id="create-form">
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
    		<div class="box-header">
    			<h3 class="box-title">{{ trans('settings.general_settings') }}</h3>
    		</div>
        		<div class="box-body">
        			<input name="_token" type="hidden" value="{{ csrf_token() }}">
    				<div class="form-group{{ $errors->has('settings_title') ? ' has-error' : null }}">
    					<label for="title" class="col-sm-2 control-label">{{ trans('settings.help_desk_title') }}</label>
    					<div class="col-sm-5">
    						<input name="settings_title" type="text" class="form-control input-sm" value="{{ old('title', config('settings.title')) }}">
    						@if ($errors->has('settings_title'))
    						<span class="help-block"><strong>{{ $errors->first('settings_title') }}</strong></span>
    						@endif
    					</div>
    				</div>
                    <div class="form-group{{ $errors->has('settings_theme') ? ' has-error' : null }}">
                        <label for="settings_theme" class="col-sm-2 control-label">{{ trans('settings.default_theme') }}</label>
                        <div class="col-sm-3">
                            <select name="settings_theme" class="form-control select2-nosearch input-sm">
                                @foreach($themes as $key => $theme)
                                    <option value="{{ $key }}"{{ old('settings_theme', config('settings.theme')) == $key ? ' selected=selected' : null }}>{{ $theme['name'] }}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('settings_theme'))
                            <span class="help-block"><strong>{{ $errors->first('system_default_dept') }}</strong></span>
                            @endif
                        </div>
                        @foreach($themes as $key => $theme)
                            @if(config('settings.theme') == $key && isset($theme['settings']))
                            <div class="col-sm-3"><p class="form-control-static"><a href="{{ route($theme['settings']) }}">{{ trans('settings.manage_theme', ['theme' => $theme['name']]) }}</a></p></div>
                            @endif
                        @endforeach
                    </div>
                    <div class="form-group{{ $errors->has('settings_default_pagesize') ? ' has-error' : null }}">
                        <label for="settings_default_pagesize" class="col-sm-2 control-label">{{ trans('settings.default_page_size') }}</label>
                        <div class="col-sm-1">
                            <select name="settings_default_pagesize" class="form-control select2-nosearch input-sm">
                                @for ($i = 5; $i <= 50; $i += 5)
                                    <option value="{{ $i }}"{{ old('settings_default_pagesize', config('settings.default.pagesize')) == $i ? ' selected=selected' : null }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @if ($errors->has('settings_default_pagesize'))
                            <span class="help-block"><strong>{{ $errors->first('settings_default_pagesize') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('settings_default_tz') ? ' has-error' : null }}">
                        <label for="settings_default_tz" class="col-sm-2 control-label">{{ trans('settings.default_time_zone') }}</label>
                        <div class="col-sm-3">
                            <select name="settings_default_tz" class="form-control select2-nosearch input-sm">
                                @foreach(timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}"{{ old('settings_default_tz', config('settings.default.tz')) == $tz ? ' selected=selected' : null }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('settings_default_tz'))
                            <span class="help-block"><strong>{{ $errors->first('settings_default_tz') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('settings_default_dept') ? ' has-error' : null }}">
                        <label for="settings_default_dept" class="col-sm-2 control-label">{{ trans('settings.default_dept') }}</label>
                        <div class="col-sm-3">
                            <select name="settings_default_dept" class="form-control select2-nosearch input-sm">
                                @foreach($depts as $id => $dept)
                                    <option value="{{ $id }}"{{ old('settings_default_dept', config('settings.default.dept')) == $id ? ' selected=selected' : null }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('settings_default_dept'))
                            <span class="help-block"><strong>{{ $errors->first('settings_default_dept') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    {{-- orgs --}}
                    <div class="form-group{{ $errors->has('settings_default_org') ? ' has-error' : null }}">
                        <label for="settings_default_org" class="col-sm-2 control-label">{{ trans('settings.default_org') }}</label>
                        <div class="col-sm-3">
                            <select name="settings_default_org" class="form-control select2-nosearch input-sm">
                                @foreach($orgs as $org)
                                    <option value="{{ $org->id }}"{{ old('settings_default_org', config('settings.default.org')) == $org->id ? ' selected=selected' : null }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('settings_default_org'))
                            <span class="help-block"><strong>{{ $errors->first('settings_default_org') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    {{-- priorities --}}
    				<div class="form-group{{ $errors->has('settings_default_priority') ? ' has-error' : null }}">
    					<label for="settings_default_priority" class="col-sm-2 control-label">{{ trans('settings.default_priority') }}</label>
    					<div class="col-sm-3">
    						<select name="settings_default_priority" class="form-control select2-nosearch input-sm">
    							@foreach($priorities as $id => $priority)
                                    <option value="{{ $id }}"{{ old('settings_default_priority', config('settings.default.priority')) == $id ? ' selected=selected' : null }}>{{ $priority }}</option>
    							@endforeach
    						</select>
    						@if ($errors->has('settings_default_priority'))
    						<span class="help-block"><strong>{{ $errors->first('settings_default_priority') }}</strong></span>
    						@endif
    					</div>
    				</div>

        		</div><!-- /.box-body -->
                <div class="box-header">
                    <h3 class="box-title">{{ trans('settings.worked_time_tracking') }}</h3>
                </div>
                <div class="form-group">
                    <label for="settings_time_enabled" class="col-sm-2 control-label">{{ trans('settings.track_time') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                            <label>
                            <input name="settings_time_enabled" type="hidden" value="0">
                            <input type="checkbox" name="settings_time_enabled" value="1"{{ old('settings_time_enabled', config('settings.time.enabled')) ? ' checked' : null }}> {{ trans('settings.enable') }}
                          </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="settings_time_edit" class="col-sm-2 control-label">{{ trans('settings.allow_date_edit') }}</label>
                    <div class="col-sm-2">
                        <div class="checkbox show">
                          <label>
                            <input name="settings_time_edit" type="hidden" value="0">
                            <input type="checkbox" name="settings_time_edit" value="1"{{ old('settings_time_edit', config('settings.time.edit')) ? ' checked' : null }}> {{ trans('settings.enable') }}
                          </label>
                        </div>
                    </div>
                </div>
                <div class="box-header">
        			<h3 class="box-title">{{ trans('settings.date_time_options') }}</h3>
        		</div>
                <div class="box-body">
    				<div class="form-group{{ $errors->has('settings_format_date') ? ' has-error' : null }}">
    					<label for="settings.format.date" class="col-sm-2 control-label">{{ trans('settings.date_format') }}</label>
    					<div class="col-sm-2">
    						<input name="settings.format.date" type="text" class="form-control input-sm" value="{{ old('settings_format_date', config('settings.format.date')) }}">
    						@if ($errors->has('settings_format_date'))
    						<span class="help-block"><strong>{{ $errors->first('settings_format_date') }}</strong></span>
    						@endif
    					</div>
    				</div>
                    <div class="form-group{{ $errors->has('settings_format_dateday') ? ' has-error' : null }}">
                        <label for="settings_format_dateday" class="col-sm-2 control-label">{{ trans('settings.dateday_format') }}</label>
                        <div class="col-sm-2">
                            <input name="settings_format_dateday" type="text" class="form-control input-sm" value="{{ old('settings_format_dateday', config('settings.format.dateday')) }}">
                            @if ($errors->has('settings_format_dateday'))
                            <span class="help-block"><strong>{{ $errors->first('settings_format_dateday') }}</strong></span>
                            @endif
                        </div>
                    </div>
    				<div class="form-group{{ $errors->has('settings_format_datetime') ? ' has-error' : null }}">
    					<label for="settings_format_datetime" class="col-sm-2 control-label">{{ trans('settings.datetime_format') }}</label>
    					<div class="col-sm-2">
    						<input name="settings_format_datetime" type="text" class="form-control input-sm" value="{{ old('settings_format_datetime', config('settings.format.datetime')) }}">
    						@if ($errors->has('settings_format_datetime'))
    						<span class="help-block"><strong>{{ $errors->first('settings_format_datetime') }}</strong></span>
    						@endif
    					</div>
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
