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
    				<div class="form-group{{ $errors->has('system_title') ? ' has-error' : null }}">
    					<label for="system_title" class="col-sm-2 control-label">{{ trans('settings.help_desk_title') }}</label>
    					<div class="col-sm-5">
    						<input name="system_title" type="text" class="form-control input-sm" value="{{ old('system_title', config('system.title')) }}">
    						@if ($errors->has('system_title'))
    						<span class="help-block"><strong>{{ $errors->first('system_title') }}</strong></span>
    						@endif
    					</div>
    				</div>
    				<div class="form-group{{ $errors->has('system_pagesize') ? ' has-error' : null }}">
    					<label for="page_size" class="col-sm-2 control-label">{{ trans('settings.default_page_size') }}</label>
    					<div class="col-sm-2">
    						<select name="system_pagesize" class="form-control select2-nosearch input-sm">
    							@for ($i = 5; $i <= 50; $i += 5)
                                    <option value="{{ $i }}"{{ old('system_pagesize', config('system.pagesize')) == $i ? ' selected=selected' : null }}>{{ $i }}</option>
    							@endfor
    						</select>
    						@if ($errors->has('system_pagesize'))
    						<span class="help-block"><strong>{{ $errors->first('system_pagesize') }}</strong></span>
    						@endif
    					</div>
    				</div>

        		</div><!-- /.box-body -->
                <div class="box-header">
        			<h3 class="box-title">{{ trans('settings.date_time_options') }}</h3>
        		</div>
                <div class="box-body">
    				<div class="form-group{{ $errors->has('system_format_date') ? ' has-error' : null }}">
    					<label for="system.format.date" class="col-sm-2 control-label">{{ trans('settings.date_format') }}</label>
    					<div class="col-sm-2">
    						<input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}">
    						@if ($errors->has('system_format_date'))
    						<span class="help-block"><strong>{{ $errors->first('system_format_date') }}</strong></span>
    						@endif
    					</div>
    				</div>
    				<div class="form-group{{ $errors->has('system_format_dateday') ? ' has-error' : null }}">
    					<label for="system_format_dateday" class="col-sm-2 control-label">{{ trans('settings.dateday_format') }}</label>
    					<div class="col-sm-2">
    						<input name="system_format_dateday" type="text" class="form-control input-sm" value="{{ old('system_format_dateday', config('system.format.dateday')) }}">
    						@if ($errors->has('system_format_dateday'))
    						<span class="help-block"><strong>{{ $errors->first('system_format_dateday') }}</strong></span>
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
