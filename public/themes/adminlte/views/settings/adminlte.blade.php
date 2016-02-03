@extends('layouts.master')

@section('title', trans('settings.emails'))

@section('content')
<section class="content-header">
	<h1>
		{{ trans('adminlte::default.adminlte_theme') }}
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
        <form method="POST" action="{{ route('theme.adminlte.update') }}" accept-charset="UTF-8" class="form-horizontal" id="create-form">
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
    		<div class="box-header">
    			<h3 class="box-title">{{ trans('settings.general_settings') }}</h3>
    		</div>
        		<div class="box-body">

                    <div class="form-group{{ $errors->has('theme_adminlte_logo') ? ' has-error' : null }}">
                        <label for="theme_adminlte_logo" class="col-sm-2 control-label">{{ trans('adminlte::default.logo') }}</label>
                        <div class="col-sm-3">
                            <input name="theme_adminlte_logo" type="text" class="form-control input-sm" value="{{ old('theme_adminlte_logo', config('theme.adminlte.logo')) }}">
                            @if ($errors->has('settings_mail_admin'))
                            <span class="help-block"><strong>{{ $errors->first('theme_adminlte_logo') }}</strong></span>
                            @endif
                        </div>
                    </div>
    				<div class="form-group{{ $errors->has('theme_adminlte_logomini') ? ' has-error' : null }}">
    					<label for="theme_adminlte_logomini" class="col-sm-2 control-label">{{ trans('adminlte::default.logo_abbr') }}</label>
    					<div class="col-sm-3">
    						<input name="theme_adminlte_logomini" type="text" class="form-control input-sm" value="{{ old('theme_adminlte_logomini', config('theme.adminlte.logomini')) }}">
    						@if ($errors->has('settings_mail_admin'))
    						<span class="help-block"><strong>{{ $errors->first('theme_adminlte_logomini') }}</strong></span>
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
