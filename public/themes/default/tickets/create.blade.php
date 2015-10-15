 @extends('layouts.master')

@section('title', 'Create Ticket')

@section('content')
<section class="content-header">
	<h1>
		Tickets
		<small>Open a new ticket</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content ticket-create">



	<div class="box box-primary">
		<div class="box-header">
			<h3 class="box-title">New Ticket</h3>
		</div>
		<div class="box-body">
			<form method="POST" action="{{ route('tickets.store') }}" accept-charset="UTF-8" class="form-horizontal" id="create-form">
				<input name="_token" type="hidden" value="{{ csrf_token() }}">
				<input name="user_id" type="hidden" value="{{ isset($user) ? $user['id'] : null }}">
				<div class="form-group{{ $errors->has('display_name') ? ' has-error' : null }}">
					<label for="user_id" class="col-sm-1 control-label">User</label>
					<div class="col-sm-5">
						<input name="display_name" type="text" class="form-control input-sm{{ isset($user) ? ' hide' : null }}" value="{{ old('display_name') }}">
						<p class="form-control-static user{{ !isset($user) ? ' hide' : null }}"><span>{{ isset($user) ? $user['display_name'] : null }}{{ isset($user) && isset($user['email']) ? ' &lt;' . $user['email'] . '&gt;' : null }}</span>
						<button type="button" class="btn btn-default btn-xs pull-right">Change</button></p>
						@if ($errors->has('display_name'))
						<span class="help-block"><strong>{{ $errors->first('display_name') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('email') ? ' has-error' : null }}{{ isset($user) ? ' hide' : null }}">
					<label for="user_id" class="col-sm-1 control-label">Email</label>
					<div class="col-sm-5">
						<input name="email" type="text" class="form-control input-sm" value="{{ old('email') }}">
						@if ($errors->has('email'))
						<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('dept_id') ? ' has-error' : null }}">
					<label for="dept_id" class="col-sm-1 control-label">Dept</label>
					<div class="col-sm-5">
						<select name="dept_id" class="form-control select2-default input-sm" placeholder="Select a Department">
							<option></option>
							@foreach ($depts as $key => $dept)
								<option value="{{ $key }}"{{ old('dept_id') == $key ? ' selected=selected' : null }}>{{ $dept }}</option>
							@endforeach
						</select>
						@if ($errors->has('dept_id'))
						<span class="help-block"><strong>{{ $errors->first('dept_id') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('assigned_id') ? ' has-error' : null }}">
					<label for="assigned_id" class="col-sm-1 control-label">Assigned</label>
					<div class="col-sm-5">
						<select name="assigned_id" class="form-control select2-default input-sm" placeholder="Select a Staff Member">
							<option></option>
							@foreach ($staff as $id => $user)
								<option value="{{ $id }}"{{ Input::old('assigned_id') == $id ? ' selected=selected' : null }}>{{ $user }}</option>
							@endforeach
						</select>
						@if ($errors->has('assigned_id'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('assigned_id') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('priority') ? ' has-error' : null }}">
					<label for="priority" class="col-sm-1 control-label">Priority</label>
					<div class="col-sm-5">
						<select name="priority" class="form-control select2-default input-sm" placeholder="Select a Priority">
							<option></option>
							<option value="1"{{ Input::old('priority') == '1' ? ' selected=selected' : null }}>1 - Business is stopped</option>
							<option value="2"{{ Input::old('priority') == '2' ? ' selected=selected' : null }}>2 - User is stopped</option>
							<option value="3"{{ Input::old('priority') == '3' ? ' selected=selected' : null }}>3 - Business is hendered</option>
							<option value="4"{{ Input::old('priority') == '4' ? ' selected=selected' : null }}>4 - User is hendered</option>
							<option value="5"{{ Input::old('priority') == '5' ? ' selected=selected' : null }}>5 - Increase productivity/savings</option>
						</select>
						@if ($errors->has('priority'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('priority') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('title') ? ' has-error' : null }}">
					<label for="title" class="col-sm-1 control-label">Summary</label>
					<div class="col-sm-9">
						<input name="title" type="text" class="form-control pull-right input-sm" value="{{ Input::old('title') }}">
						@if ($errors->has('title'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('title') }}</strong></span>
						@endif
					</div>
				</div>
				<div class="form-group{{ $errors->has('body') ? ' has-error' : null }}">
					<label for="body" class="col-sm-1 control-label">Details</label>
					<div class="col-sm-9">
						<textarea name="body" rows="5" class="form-control pull-right input-sm">{{ Input::old('body') }}</textarea>
						@if ($errors->has('body'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('body') }}</strong></span>
						@endif
					</div>
				</div>
				<ul class="nav nav-tabs">
					<li{!! is_null(Session::get('type')) || Session::get('type') == 'reply' ? ' class="active"' : '' !!}><a href="#reply" data-toggle="tab">Reply</a></li>
					<li{!! Session::get('type') == 'comment' ? ' class="active"' : '' !!}><a href="#comment" data-toggle="tab">Comment</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane{!! Session::get('type') == null || Session::get('type') == 'reply' ? ' active' : '' !!}" id="reply">
						<div class="form-group{!! $errors->has('status') ? ' has-error' : null !!}">
							<label class="col-md-1 control-label" for="textinput">Status</label>
							<div class="col-md-3">
								<select name="status" class="form-control select2-default input-sm">
									<option value="open"{{ Input::old('status') == 'open' ? ' selected=selected' : null }}>Open</option>
									<option value="closed"{{ Input::old('status') == 'closed' ? ' selected=selected' : null }}>Closed</option>
									<option value="resolved"{{ Input::old('status') == 'resolved' ? ' selected=selected' : null }}>Resolved</option>
								</select>
							</div>
						</div>
						<div class="form-group{{ $errors->has('reply_body') ? ' has-error' : null }}">
							<div class="col-md-9">
								<textarea class="textarea form-control input-sm" name="reply_body" placeholder="Enter a response here" style="height: 100px;">{{ Input::old('reply_body') }}</textarea>
								@if ($errors->has('reply_body'))
								<span id="helpBlock" class="help-block"><strong>{{ $errors->first('reply_body') }}</strong></span>
								@endif
							</div>
						</div>
					</div>
					<div class="tab-pane{{ Session::get('type') == 'comment' ? ' active' : '' }}" id="comment">
						<div class="form-group{{ $errors->has('comment_body') ? ' has-error' : null }}">
							<div class="col-md-9">
								<textarea class="textarea form-control input-sm" name="comment_body" placeholder="Enter a comment here" style="height: 100px;">{{ Input::old('comment_body') }}</textarea>
								@if ($errors->has('comment_body'))
								<span id="helpBlock" class="help-block"><strong>{{ $errors->first('comment_body') }}</strong></span>
								@endif
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="form-group{{ $errors->has('time_spent') ? ' has-error' : null }}">
					<label class="col-md-1 control-label" for="textinput">Worked</label>
					<div class="col-md-1">
						<input id="textinput" name="time_spent" type="text" value="{{ Input::old('time_spent') }}" class="form-control input-sm">
					</div>
					@if ($errors->has('time_spent'))
					<div class="col-md-10">
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('time_spent') }}</strong></span>
					</div>
					@endif
				</div> -->
				<div class="row">
					<div class="col-md-3">
						<div class="form-group{{ $errors->has('hours') ? ' has-error' : null }}">
							<label class="col-md-6 control-label" for="hours">Worked Hours</label>
							<div class="col-md-6">
								<input id="textinput" name="hours" type="text" value="{{ Input::old('hours') }}" class="form-control input-sm">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group{{ $errors->has('time_at') ? ' has-error' : null }}">
							<label class="col-md-6 control-label" for="date">Worked Date</label>
							<div class="col-md-6">
								<input id="textinput" name="time_at" type="text" value="{{ old('time_at') ? old('time_at') : date('m/d/Y') }}" class="form-control input-sm singledate">
							</div>
						</div>
					</div>
					<div class="col-md-6"></div>
				</div>
				@if ($errors->has('status') || $errors->has('hours') || $errors->has('time_at'))
				<div class="row">
					<div class="col-md-4">
						@if ($errors->has('status'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
						@endif
					</div>
					<div class="col-md-4">
						@if ($errors->has('hours'))
						<span id="helpBlock" class="help-block"><strong>{{ $errors->first('hours') }}</strong></span>
						@endif
					</div>
				</div>
				@endif
				<hr/>
				<button class="btn btn-primary">Create</button>
			</form>
		</div><!-- /.box-body -->

		<!-- right column -->

	</div>
	@include('common.modals.create_user')
</section>
@stop
