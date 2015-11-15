@extends('layouts.master')

@section('title', 'Show Ticket #' . $ticket['id'])

@section('content')
<section class="content-header">
	<h1>
		{{ $ticket['actions'][0]['title'] }}
		<!-- <small>#{{ $ticket['id'] }}</small> -->
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-9">
		<div class="row">
		<div class="col-md-12">
			<!-- The time line -->
			<ul class="timeline">
				<!-- timeline time label -->
				<li class="time-label">
					<span class="bg-red">{{ $ticket['created_at']->tz(auth()->user()->timezone)->format('j M Y') }}{{-- */ $lastday = $ticket['created_at'] /*--}}</span>
					<div class="btn-group pull-right">
						<a href="#reply" class="btn btn-default go-show-tab">Reply</a>
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
							<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#comment" class="go-show-tab">Comment</a></li>
							<li><a href="#transfer" class="go-show-tab">Transfer</a></li>
							<li><a href="#assign" class="go-show-tab">Assign</a></li>
							<li class="divider"></li>
							<li><a href="{{ route('tickets.edit', $ticket['id']) }}">Edit</a></li>
						</ul>
                    </div>
				</li>
				{{--<!-- /.timeline-label -->
				<!-- timeline item -->
				<!-- <li>
					<i class="fa fa-desktop bg-blue"></i>
					<div class="timeline-item">
						<span class="time"><i class="fa fa-clock-o"></i> {{ $ticket['created_at']->format('g:i a') }}</span>
						<h3 class="timeline-header"><a href="#">{{ $ticket['staff']['user']['display_name'] }}</a> created a ticket for <a href="#">{{ $ticket['user']['display_name'] }}</a></h3>
						<div class="timeline-body">
							<h5>{{ $ticket['subject'] }}</h5>
							{{ $ticket['description'] }}
						</div>
						<div class='timeline-footer'>
							<a class="btn btn-primary btn-xs">Read more</a>
							<a class="btn btn-danger btn-xs">Delete</a>
						</div>

					</div>
				</li> -->--}}
				@foreach ($ticket['actions'] as $action)
				@if (!isset($lastday) || !$action['created_at']->tz(auth()->user()->timezone)->isSameDay($lastday))
				<li class="time-label">
					<span class="bg-red">{{ $action['created_at']->tz(auth()->user()->timezone)->format('j M Y') }}</span>
				</li>
				{{-- */$lastday = $action['created_at'];/* --}}
				@endif
				<!-- /.timeline-label -->
				<!-- timeline item -->
				<li id="action-{{ $action['id'] }}">
					<i class="fa fa-desktop bg-blue"></i>
					<div class="timeline-item">
						<ul class="list-inline time">
							@if (!is_null($action['time']))
							<li><span  data-toggle="tooltip" title="{{ $action['time']['hours'] }} hour(s) on {{ $action->time->time_at->tz(auth()->user()->timezone)->format(config('system.format.date')) }}"><i class="fa fa-wrench"></i> {{ $action['time']['hours'] }}</span></li>
							@endif
							<li><i class="fa fa-clock-o"></i> {{ $action->created_at->tz(auth()->user()->timezone)->format('g:i a') }}</li>
						</ul>
						<h3 class="timeline-header{{ $action['message'] == null ? ' no-border' : '' }}">
							@if ($action['user_id'] == '0')
							System
							@else
							<a href="#">{{ $action['user']['display_name'] }}</a>
							@endif
							@if ($action['type'] == 'reply')
							replied to ticket
							@elseif ($action['type'] == 'comment')
							commented on ticket
							@elseif (in_array($action['type'], ['closed', 'resolved']))
							{{ $action['type'] }} the ticket
							@elseif ($action['type'] == 'edit')
							edited ticket
							@elseif ($action['type'] == 'transfer')
							transfered ticket to {{ $action['transfer']['name']}}
							@elseif ($action['type'] == 'assign')
							assigned ticket to <a href="#">{{ $action->assigned->display_name or trans('nobody') }}</a>
							@elseif ($action['type'] == 'create')
							created ticket
							@elseif ($action['type'] == 'open')
							opened ticket
							@endif
						</h3>
						@if($action['body'] != null)
						<div class="timeline-body">
							{!! parse_links(nl2br($action['body'])) !!}
						</div>
						@endif
					</div>
				</li>
				@endforeach


				<!-- END timeline item -->
				<li>
					<i class="fa fa-clock-o"></i>
				</li>
			</ul>

			</div></div>
			<div class="row act">
		<div class="col-md-12">
			<div class="nav-tabs-custom" id="action">
				<ul class="nav nav-tabs">
					<!-- <li class="active"><a href="#quick" data-toggle="tab">Quick</a></li> -->
					<li{!! Session::get('type') == null || Session::get('type') == 'reply' ? ' class="active"' : '' !!}><a href="#reply" data-toggle="tab">Reply {{ Session::get('type') }}</a></li>
					<li{!! Session::get('type') == 'comment' ? ' class="active"' : '' !!}><a href="#comment" data-toggle="tab">Comment</a></li>
					<li{!! Session::get('type') == 'transfer' ? ' class="active"' : '' !!}><a href="#transfer" data-toggle="tab">Dept Transfer</a></li>
					<li{!! Session::get('type') == 'assign' ? ' class="active"' : '' !!}><a href="#assign" data-toggle="tab">Assign</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane{!! Session::get('type') == null || Session::get('type') == 'reply' ? ' active' : '' !!}" id="reply">
						<form method="POST" action="{{ route('actions.store') }}" accept-charset="UTF-8" class="form-horizontal">
						<input name="_token" type="hidden" value="{{ csrf_token() }}">
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<input name="type" type="hidden" value="reply">
						<div class="form-group{{ Input::old('type') == 'reply' && $errors->has('body') ? ' has-error' : null }}">
							<div class="col-md-12">
								@if (Input::old('type') == 'reply' && ($errors->has('body') || $errors->has('hours') || $errors->has('status')))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								<textarea class="textarea form-control" name="body" placeholder="Enter a response here" style="height: 100px;">{{ Input::old('type') == 'reply' ? Input::old('body'): null }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label" for="textinput">Status</label>
									<div class="col-md-8">
										<select name="status" class="form-control select2-default input-sm{!! Input::old('type') == 'reply' && $errors->has('status') ? ' has-error' : null !!}">
											<option value="open"{!! Input::old('status') == 'open' ? ' selected=selected' : null !!}>Open</option>
											<option value="closed"{!! Input::old('status') == 'closed' ? ' selected=selected' : null !!}>Closed</option>
											<option value="resolved"{!! Input::old('status') == 'resolved' ? ' selected=selected' : null !!}>Resolved</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{!! $errors->has('hours') && Input::old('type') == 'reply' ? ' has-error' : null !!}">
									<label class="col-md-6 control-label" for="textinput">Worked Hours</label>
									<div class="col-md-6">
										<input id="textinput" name="hours" type="text" value="{{ Input::old('type') == 'reply' ? Input::old('hours') : null }}" class="form-control input-sm">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('time_at') ? ' has-error' : null }}">
									<label class="col-md-6 control-label" for="date">Worked Date</label>
									<div class="col-md-6">
										<input id="textinput" name="time_at" type="text" value="{{ old('time_at') ? old('time_at') : date('m/d/Y') }}" class="form-control input-sm singledate">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Post Reply">
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane{!! Session::get('type') == 'comment' ? ' active' : '' !!}" id="comment">
						<form method="POST" action="{{ route('actions.store') }}" accept-charset="UTF-8" class="form-horizontal">
						<input name="_token" type="hidden" value="{{ csrf_token() }}">
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<input name="type" type="hidden" value="comment">
						<div class="form-group{!! Input::old('type') == 'comment' && $errors->has('body') ? ' has-error' : null !!}">
							<div class="col-md-12">
								@if (Input::old('type') == 'comment' && ($errors->has('body') || $errors->has('hours')))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								<textarea class="textarea form-control" name="body" placeholder="Enter a internal comment here" style="height: 100px;">{{ Input::old('type') == 'comment' ? Input::old('body') : null }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-4">

								<div class="form-group{!! Input::old('type') == 'comment' && $errors->has('hours') ? ' has-error' : null !!}">
									<label class="col-md-6 control-label" for="textinput">Worked Hours</label>
									<div class="col-md-6">
										<input id="textinput" name="hours" type="text" value="{!! Input::old('type') == 'comment' ? Input::old('hours') : null !!}" class="form-control input-sm">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group{{ $errors->has('time_at') ? ' has-error' : null }}">
									<label class="col-md-6 control-label" for="date">Worked Date</label>
									<div class="col-md-6">
										<input id="textinput" name="time_at" type="text" value="{{ old('time_at') ? old('time_at') : date('m/d/Y') }}" class="form-control input-sm singledate">
									</div>
								</div>
							</div>
							<div class="col-md-4">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Post Comment">
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane{!! Session::get('type') == 'transfer' ? ' active' : '' !!}" id="transfer">
					<form method="POST" action="{{ route('actions.store') }}" accept-charset="UTF-8" class="form-horizontal">
						<input name="_token" type="hidden" value="{{ csrf_token() }}">
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<input name="type" type="hidden" value="transfer">
						<div class="form-group{!! Input::old('type') == 'transfer' && $errors->has('transfer_id') ? ' has-error' : null !!}">
							<div class="col-md-6">
								@if (Input::old('type') == 'transfer' && ($errors->has('body') || $errors->has('transfer_id')))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								<select name="transfer_id" class="form-control select2-default input-sm" placeholder="Select a Department">
									<option></option>
									@foreach ($depts as $key => $dept)
										<option value="{{ $key }}"{{ Input::old('type') == 'transfer' && Input::old('transfer_id') == $key ? ' selected=selected' : null }}>{{ $dept }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group{!! Input::old('type') == 'transfer' && $errors->has('body') ? ' has-error' : null !!}">
							<div class="col-md-12">
								<textarea class="textarea form-control" name="body" placeholder="Enter reasons for the transfer" style="height: 100px;">{{ Input::old('type') == 'transfer' ? Input::old('body') : null }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
										<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Transfer">
							</div>
						</div>
						</form>
					</div>
					<div class="tab-pane{!! Session::get('type') == 'assign' ? ' active' : '' !!}" id="assign">
						<form method="POST" action="{{ route('actions.store') }}" accept-charset="UTF-8" class="form-horizontal">
						<input name="_token" type="hidden" value="{{ csrf_token() }}">
						<input name="ticket_id" type="hidden" value="{{ $ticket['id'] }}">
						<input name="type" type="hidden" value="assign">
						<div class="form-group{!! Input::old('type') == 'assign' && $errors->has('transfer_id') ? ' has-error' : null !!}">
							<div class="col-md-6">
								@if ( Input::old('type') == 'assign' && ($errors->has('body') || $errors->has('assigned_id')))
								<ul class="list-unstyled">
								@foreach ($errors->all() as $error)
									<li class="text-red"><strong>{{ $error }}</strong></li>
								@endforeach
								</ul>
								@endif
								<select name="assigned_id" class="form-control select2-default input-sm" placeholder="Select a Staff Member">
									<option></option>
									@foreach (array_except($staff->put(0, 'Nobody')->toArray(), [$ticket->assigned_id]) as $key => $user)
										<option value="{{ $key }}"{{ Input::old('assigned_id') == $key ? ' selected=selected' : null }}>{{ $user }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group{!! Input::old('type') == 'assign' && $errors->has('body') ? ' has-error' : null !!}">
							<div class="col-md-12">
								<textarea class="textarea form-control" name="body" placeholder="Enter reasons for the assignment or instructions for assignee" style="height: 100px;">{{ Input::old('type') == 'assign' ? Input::old('body') : null }}</textarea>

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input class="btn btn-primary btn-sm" type="submit" name="action_reply" value="Assign">
							</div>
						</div>
						</form>
					</div>
				</div><!-- /.tab-content -->
				</div></div>
			</div>
		</div>
		<div class="col-md-3">
			<!-- general form elements -->
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Details</h3>
					{{-- <div class="box-tools pull-right">
	                    <button class="btn btn-box-tool btn-block btn-sm">Edit</button>
                  	</div> --}}
				</div><!-- /.box-header -->
				<!-- form start -->
				<form role="form">
					<div class="box-body">

						<div class="row">
							<div class="col-xs-12">
								<dl class="dl-horizontal detail">
									<dt>Id</dt>
									<dd>{{ $ticket['id'] }}</dd>
									<dt>Status</dt>
									<dd>{{ $ticket['status'] }}</dd>
									<dt>Priority</dt>
									<dd>{{ $ticket['priority'] }}</dd>
									<dt>Department</dt>
									<dd>{{ $ticket['dept']['name'] }}</dd>
									<dt>User</dt>
									<dd><a href="#">{{ $ticket['user']['display_name'] }}</a></dd>
									<dt>Phone</dt>
									<dd></dd>
								</dl>
							</div>
						</div>
					</div><!-- /.box-body -->

					<div class="box-footer">
						<div class="row">
							<div class="col-xs-12">
								<dl class="dl-horizontal detail">
									<dt>Assigned</dt>
									<dd>{{ $ticket['assigned']['display_name'] or trans('nobody') }}</dd>
									<dt>Total Hours</dt>
									<dd>{{ $ticket['hours'] }}</dd>
									<dt>Last Action</dt>
									<dd>{{ is_null($ticket['last_action_at']) ? 'None' : date_format($ticket['last_action_at'], config('system.format.date')) }}</dd>
								</dl>
							</div>
						</div>
					</div>
				</form>
			</div><!-- /.box -->




		</div><!--/.col (left) -->
		<!-- right column -->

	</div>
</section>
@stop
