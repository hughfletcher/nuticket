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
				<input name="user_id" type="hidden" value="{{ isset($user) ? $user['id'] : null }}">
				<div class="form-group{{ $errors->has('display_name') ? ' has-error' : null }}">
					<label for="user_id" class="col-sm-1 control-label">User</label>
					<div class="col-sm-5">
						<p class="form-control-static user"><span>{{ $ticket->user->display_name }}{{ !is_null($ticket->user->email) ? ' &lt;' . $ticket->user->email . '&gt;' : null }}</span> 
						<button type="button" class="btn btn-default btn-xs pull-right">Change</button></p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('priority') ? ' has-error' : null }}">
					<label for="priority" class="col-sm-1 control-label">Priority</label>
					<div class="col-sm-5">
						<input name="priority" type="text" class="form-control select2-priorities input-sm" value="{{ Input::old('priority') ? Input::old('priority') : $ticket['priority'] }}">
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