@extends('layouts.master')

@section('title', 'Tickets')

@section('content')
<section class="content-header">
	<h1>
		Tickets
		<small>Control panel</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content">

	@if (!$errors->isEmpty())
	<div class="alert alert-danger alert-dismissable">
		<i class="fa fa-ban"></i>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<b>Error!</b> {{ $errors->first() }}
	</div>
	@endif
	<div class="mailbox row">
		<div class="col-xs-12">
			<div class="box box-solid">
				<div class="box-body">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="row pad">
								<div class="col-sm-6">
									<div class="btn-group">
										<a href="{{ route('tickets.index', ['status' => ['new', 'open']]) }}" class="btn btn-default btn-flat btn-sm{{ count(Request::query()) == 2 && Request::query('status') == 'new-open'? ' active' : '' }}">Open ({{ $open_count }}) </a>
										<a href="{{ route('tickets.index', ['status' => ['closed']]) }}" class="btn btn-default btn-flat btn-sm{{ count(Request::query()) == 2 && Request::query('status') == 'closed' ? ' active' : '' }}">Closed ({{ $close_count }})</a>
										@if(Auth::user()->is_staff)
										<a href="{{ route('tickets.index', ['assigned_id' => [Auth::user()->id], 'status' => ['new', 'open']]) }}" class="btn btn-default btn-flat btn-sm{{ count(Request::query()) == 3 && Request::query('status') == 'new-open' && Request::query('assigned_id') == Auth::user()->id ? ' active' : '' }}">Assigned ({{ $assigned_count }})</a>
										@endif
									</div>

								</div>
								<div class="col-sm-6 search-form">
									<form action="{{ route('tickets.index') }}" class="text-right form-inline">
										<div class="input-group">
											<input type="text" name="q" value="{{ Input::get('q') }}" class="form-control input-sm" placeholder="Search">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
											</div>
										</div>
										<div class="form-group">
											<a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filter"><i class="fa fa-filter"></i></a>
										</div>
									</form>
								</div>
							</div><!-- /.row -->
							<div class="box-body">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th><a href="{{ sort_url('id') }}">Id<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('id', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('created_at') }}">Created<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('created_at', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('last_action_at') }}">Last Action<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('last_action_at', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('title') }}">Subject<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('title', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('user') }}">From<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('user', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('priority') }}">Priority<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('priority', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('assigned') }}">Assigned<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('assigned', null, '-') }}"></i></span></a></th>
										</tr>
									</thead>

									<tbody>
										@if ($tickets->count() >= 1)
										@foreach ($tickets as $ticket)
										<tr>
											<td><a href="{{ route('tickets.show', [$ticket['id'], '#action']) }}">{{ $ticket['id'] }}</a></td>
											<td>{{ $ticket->created_at->tz(auth()->user()->timezone)->format(config('settings.format.date')) }}</td>
											<td>{{ is_null($ticket['last_action_at']) ? 'None' : $ticket->last_action_at->tz(auth()->user()->timezone)->format(config('settings.format.date')) }}</td>
											<td><a href="{{ route('tickets.show', [$ticket['id']]) }}">{{ $ticket->title }}</a></td>
											<td>{{ $ticket->user->display_name }}</td>
											<td>{{ $ticket['priority'] }}</td>
											<td>{{ $ticket->assigned->display_name or trans('nobody') }}</td>
										</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<th>Id</th>
											<th>Created</th>
											<th>Last Action</th>
											<th>Subject</th>
											<th>From</th>
											<th>Priority</th>
											<th>Assigned</th>
										</tr>
									</tfoot>
									@else
									<tr>
										<td colspan="7" class="text-center">There are no tickets to view</td>
									</tr>

								@endif

								</table>
								<div class="row">
									<div class="col-xs-4">
										<div class="table_info" id="example2_info">Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} tickets</div>
									</div>
									<div class="col-xs-8">
										<div class="table_paginate paging_bootstrap">
											{!! $tickets->appends(Request::query())->render() !!}
										</div>
									</div>
								</div>
							</div>
						</div><!-- /.col (RIGHT) -->
					</div><!-- /.row -->
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div><!-- /.col (MAIN) -->
	</div>

	<div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fa fa-search"></i> Advanced Search</h4>
				</div>
				<form action="{{ route('tickets.index') }}" method="get" enctype="text/plain">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<input class="form-control input-sm" name="q" placeholder="Keywords - Optional">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Status</label>
									<select name="status[]" class="form-control select2-default input-sm" placeholder="Leave empty for any" multiple>
										<option></option>
										<option value="new">New</option>
										<option value="open">Open</option>
										<option value="resolved">Resolved</option>
										<option value="closed">Closed</option>
									</select>
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Department</label>
									<select name="dept_id[]" class="form-control select2-default input-sm" placeholder="Leave empty for any" multiple>
										<option></option>
										@foreach ($depts as $key => $dept)
											<option value="{{ $key }}"{{ old('dept_id') == $key ? ' selected=selected' : null }}>{{ $dept }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="org_id" class="control-label">Organization</label>
										<select class="form-control input-sm select2-default" name="org_id[]" data-placeholder="Leave empty for all" multiple>
											<option></option>
											@foreach($orgs as $org)
											<option value="{{ $org->id }}">{{ $org->name }}</option>
											@endforeach
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="assigned_id" class="control-label">Assigned To</label>
									<select name="assigned_id[]" class="form-control select2-default input-sm" placeholder="Leave empty for any" multiple>
										<option></option>
										@foreach ($staff as $id => $user)
											<option value="{{ $id }}">{{ $user }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Priority</label>
									<select name="priority[]" class="form-control select2-default input-sm" placeholder="Leave empty for any" multiple>
										<option></option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
								<div class="form-group">
									<label>Date Range [Create Date]:</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" class="form-control pull-right input-sm daterange" name="created_at" placeholder="Leave empty for all"/>
									</div>
								</div>
							</div>
						</div>

{{-- 						<div class="row">
							<div class="col-md-12">
								<label>Date Range [Create Date]:</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
									<input type="text" class="form-control pull-right input-sm daterange" name="created_at" placeholder="Leave empty for all"/>
								</div>
							</div>
						</div> --}}

					</div>
					<div class="modal-footer clearfix">

						<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

						<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
					</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
</section><!-- /.modal -->

@stop
