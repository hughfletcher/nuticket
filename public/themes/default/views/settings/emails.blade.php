@extends('layouts.master')

@section('title', trans('settings.emails'))

@section('content')
<section class="content-header">
	<h1>
		{{ trans('settings.emails') }}
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
        <form method="POST" action="{{ route('settings.update', ['emails']) }}" accept-charset="UTF-8" class="form-horizontal" id="create-form">
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
    		<div class="box-header">
    			<h3 class="box-title">{{ trans('settings.general_settings') }}</h3>
    		</div>
        		<div class="box-body">
    				<div class="form-group{{ $errors->has('mail_default') ? ' has-error' : null }}">
    					<label for="page_size" class="col-sm-2 control-label">{{ trans('settings.default_system_email') }}</label>
    					<div class="col-sm-4">
    						<select name="mail_default" class="form-control select2-nosearch input-sm">
    							@foreach ($emails as $email)
                                    <option value="{{ $email->id }}"{{ old('mail_default', config('mail.default')) == $email->id ? ' selected=selected' : null }}>{{ $email->name . ' <' . $email->email . '>' }}</option>
    							@endforeach
    						</select>
    						@if ($errors->has('mail_default'))
    						<span class="help-block"><strong>{{ $errors->first('mail_default') }}</strong></span>
    						@endif
    					</div>
    				</div>

    				<div class="form-group{{ $errors->has('mail_admin') ? ' has-error' : null }}">
    					<label for="system_title" class="col-sm-2 control-label">{{ trans('settings.admin_email_address') }}</label>
    					<div class="col-sm-4">
    						<input name="mail_admin" type="text" class="form-control input-sm" value="{{ old('mail_admin', config('mail.admin')) }}">
    						@if ($errors->has('mail_admin'))
    						<span class="help-block"><strong>{{ $errors->first('mail_admin') }}</strong></span>
    						@endif
    					</div>
    				</div>

        		</div><!-- /.box-body -->
                <div class="box-header">
        			<h3 class="box-title">{{ trans('settings.incoming_emails') }}</h3>
        		</div>
                <div class="box-body">
    				<div class="form-group">
    					<label for="system.format.date" class="col-sm-2 control-label">{{ trans('settings.email_fetching') }}</label>
    					<div class="col-sm-2">
    						{{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                            <div class="checkbox show">
                              <label>
                                <input type="checkbox" name="mail_fetch" value="true"{{ config('mail.fetch') ? ' checked' : null }}> {{ trans('settings.enable') }}
                              </label>
                            </div>
    					</div>
    				</div>
                    <div class="form-group{{ $errors->has('mail_replyseperator') ? ' has-error' : null }}">
                        <label for="system_format_dateday" class="col-sm-2 control-label">{{ trans('settings.reply_seperator_tag') }}</label>
                        <div class="col-sm-4">
                            <input name="mail_replyseperator" type="text" class="form-control input-sm" value="{{ old('mail_replyseperator', config('mail.replyseperator')) }}">
                            @if ($errors->has('mail_replyseperator'))
                            <span class="help-block"><strong>{{ $errors->first('mail_replyseperator') }}</strong></span>
                            @endif
                        </div>
                    </div>
    				<div class="form-group">
    					<label for="system.format.date" class="col-sm-2 control-label">{{ trans('settings.emailed_tickets_priority') }}</label>
    					<div class="col-sm-2">
    						{{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                            <div class="checkbox show">
                              <label>
                                <input type="checkbox" name="mail_keeppriority" value="true"{{ config('mail.keeppriority') ? ' checked' : null }}> {{ trans('settings.enable') }}
                              </label>
                            </div>
    					</div>
    				</div>
    				<div class="form-group">
    					<label for="system.format.date" class="col-sm-2 control-label">{{ trans('settings.accept_all_emails') }}</label>
    					<div class="col-sm-2">
    						{{-- <input name="system.format.date" type="text" class="form-control input-sm" value="{{ old('system_format_date', config('system.format.date')) }}"> --}}
                            <div class="checkbox show">
                              <label>
                                <input type="checkbox" name="mail_acceptunknown" value="true"{{ config('mail.acceptunknown') ? ' checked' : null }}> {{ trans('settings.enable') }}
                              </label>
                            </div>
    					</div>
    				</div>
        		</div>
                <div class="box-header">
                    <h3 class="box-title">{{ trans('settings.outgoing_emails') }}</h3>
                </div>
                <div class="box-body">
    				<div class="form-group{{ $errors->has('mail_defaultmta') ? ' has-error' : null }}">
    					<label for="page_size" class="col-sm-2 control-label">{{ trans('settings.default_mta') }}</label>
    					<div class="col-sm-4">
    						<select name="mail_defaultmta" class="form-control select2-nosearch input-sm">
    							@foreach ($mtas as $mta)
                                    <option value="{{ $mta->id }}"{{ old('mail_defaultmta', config('mail.defaultmta')) == $mta->id ? ' selected=selected' : null }}>{{ $mta->name . ' <' . $mta->email . '>' }}</option>
    							@endforeach
    						</select>
    						@if ($errors->has('mail_defaultmta'))
    						<span class="help-block"><strong>{{ $errors->first('mail_defaultmta') }}</strong></span>
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
