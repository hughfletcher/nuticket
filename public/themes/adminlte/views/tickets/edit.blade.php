@extends('layouts.master')

@section('title', 'Edit Ticket #' . $ticket['id'])

@section('content')
<section class="content-header">
	<h1>
		{{ $ticket['actions'][0]['title'] }}
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content">

	

	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">Edit Ticket</h3>
		</div>
		<div class="box-body">
			@if (Session::get('message'))
			<div class="alert alert-danger" role="alert">
		      	{{ Session::get('message') }}
		    </div>
		    @endif
			<form method="POST" action="{{ route('tickets.update', [$ticket['id']]) }}" accept-charset="UTF-8" class="form-horizontal" id="edit-form">
				<input name="_method" type="hidden" value="PUT">
				<input name="_token" type="hidden" value="{{ csrf_token() }}">
				<input name="user_id" type="hidden" value="{{ $ticket->user->id }}">
				<div class="form-group{{ $errors->has('display_name') ? ' has-error' : null }}">
					<label for="user_id" class="col-sm-1 control-label">User</label>
					<div class="col-sm-5">
						<p class="form-control-static user"><span>{{ $ticket->user->display_name }}{{ !is_null($ticket->user->email) ? ' &lt;' . $ticket->user->email . '&gt;' : null }}</span> 
						<button type="button" class="btn btn-default btn-xs pull-right">Change</button></p>
					</div>
				</div>
				
				<div class="form-group{{ $errors->has('org_id') ? ' has-error' : null }}">
					<label for="org_id" class="col-sm-1 control-label">Orginization</label>
					<div class="col-sm-5">
						<select class="form-control input-sm select2-default org" name="org_id" data-placeholder="Select An Organization">
							<option></option>
							@foreach($orgs as $org)
								<option value="{{ $org->id }}"{{ old('org_id') == $org->id || $ticket->org->id == $org->id ? ' selected=selected' : null }}>{{ $org->name }}</option>
							@endforeach
							</select>
						@if ($errors->has('org_id'))
						<span class="help-block"><strong>{{ $errors->first('org_id') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('priority') ? ' has-error' : null }}">
					<label for="priority" class="col-sm-1 control-label">Priority</label>
					<div class="col-sm-5">
						<select name="priority" class="form-control select2-default input-sm">
							<option></option>
							<option value="1"{{ old('priority') == '1' || $ticket['priority'] == '1' ? ' selected=selected' : null }}>1 - Business is stopped</option>
							<option value="2"{{ old('priority') == '2' || $ticket['priority'] == '2' ? ' selected=selected' : null }}>2 - User is stopped</option>
							<option value="3"{{ old('priority') == '3' || $ticket['priority'] == '3' ? ' selected=selected' : null }}>3 - Business is hendered</option>
							<option value="4"{{ old('priority') == '4' || $ticket['priority'] == '4' ? ' selected=selected' : null }}>4 - User is hendered</option>
							<option value="5"{{ old('priority') == '5' || $ticket['priority'] == '5' ? ' selected=selected' : null }}>5 - Increase productivity/savings</option>
						</select>
						@if ($errors->has('priority'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('priority') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('title') ? ' has-error' : null }}">
					<label for="title" class="col-sm-1 control-label">Summary</label>
					<div class="col-sm-9">
						<input name="title" type="text" class="form-control pull-right input-sm" value="{{ Input::old('title') ? Input::old('title') : $ticket['actions'][0]['title'] }}">
						@if ($errors->has('title'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('title') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('body') ? ' has-error' : null }}">
					<label for="body" class="col-sm-1 control-label">Details</label>
					<div class="col-sm-9">
						<textarea name="body" rows="5" class="form-control pull-right input-sm">{{ Input::old('body') ? Input::old('body') : $ticket['actions'][0]['body'] }}</textarea>
						@if ($errors->has('body'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('body') }}</strong></span>
						@endif
					</div>
				</div>
				<hr>
				<div class="form-group{{ $errors->has('reason') ? ' has-error' : null }}">
					<label for="body" class="col-sm-1 control-label">Reason</label>
					<div class="col-md-9">
						<textarea class="textarea form-control input-sm" name="reason" placeholder="Enter a reason for edit" style="height: 100px;">{{ Input::old('reason') }}</textarea>
						@if ($errors->has('reason'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('reason') }}</strong></span>
						@endif
					</div>
				</div>
				<hr/>
				<button class="btn btn-primary btn-sm">Edit</button>
				<a href="{{ URL::previous() }}" class="btn btn-default btn-sm">Cancel</a>
			</form>
		</div><!-- /.box-body -->

		<!-- right column -->

	</div>
	@include('common.modals.create_user')
</section>
@stop