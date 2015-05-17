{{-- @extends('layouts.master') --}}

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

	@if ($errors)
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
										<a href="{{ route('tickets.index', ['status' => 'new-open']) }}" class="btn btn-default btn-flat btn-sm{{ count(Request::query()) == 2 && Request::query('status') == 'new-open'? ' active' : '' }}">Open ({{ $open_count }}) </a>
										<a href="{{ route('tickets.index', ['status' => 'closed']) }}" class="btn btn-default btn-flat btn-sm{{ count(Request::query()) == 2 && Request::query('status') == 'closed' ? ' active' : '' }}">Closed ({{ $close_count }})</a>
										@if($is_staff)
										<a href="{{ route('tickets.index', ['staff_id' => user('staff')->id, 'status' => 'new-open']) }}" class="btn btn-default btn-flat btn-sm{{ count(Request::query()) == 3 && Request::query('status') == 'new-open' && Request::query('staff_id') == Auth::user()->staff->id ? ' active' : '' }}">Assigned ({{ $assigned_count }})</a>
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
											<a href="#" class="form-control-static" data-toggle="modal" data-target="#compose-modal">advanced</a>
										</div>
									</form>
								</div>
							</div><!-- /.row -->

							<div class="box-body table-responsive">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th><a href="{{ sort_url('id') }}">Id<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('id', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('last_action_at') }}">Date<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('last_action_at', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('subject') }}">Subject<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('subject', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('user') }}">From<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('user', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('priority') }}">Priority<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('priority', null, '-') }}"></i></span></a></th>
											<th><a href="{{ sort_url('staff') }}">Assigned<span class="pull-right"><i class="fa fa-fw fa-sort{{ order('staff', null, '-') }}"></i></span></a></th>
										</tr>
									</thead>
									
									<tbody>
										@if ($tickets->count() >= 1)
										@foreach ($tickets as $ticket)
										<tr>
											<td>{{ link_to_route('tickets.show', $ticket['id'], [$ticket['id'], '#action']) }}</td>
											<td>{{ datetime($ticket['last_action_at']) }}</td>
											<td>{{ link_to_route('tickets.show', $ticket['subject'], [$ticket['id']]) }}</td>
											<td>{{ $ticket['user'] }}</td>
											<td>{{ $ticket['priority'] }}</td>
											<td>{{ $ticket['staff'] }}</td>
										</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<th>Id</th>
											<th>Date</th>
											<th>Subject</th>
											<th>From</th>
											<th>Priority</th>
											<th>Assigned</th>
										</tr>
									</tfoot>
									@else
									<tr>
										<td colspan="6" class="text-center">There are no tickets to view</td>
									</tr>
								
								@endif

								</table>
								<div class="row">
									<div class="col-xs-4">
										<div class="table_info" id="example2_info">Showing {{ $tickets->getFrom() }} to {{ $tickets->getTo() }} of {{ $tickets->getTotal() }} tickets</div>
									</div>
									<div class="col-xs-8">
										<div class="table_paginate paging_bootstrap">
											{{ $tickets->appends(Request::query())->links() }}
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

	<div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
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
								<input class="form-control" name="q" placeholder="Keywords - Optional">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Status</label>
									<input class="status-select form-control" placeholder="Leave empty for any" name="status">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Priority</label>
									<input class="priority-select form-control" placeholder="Leave empty for any" name="priority">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Assigned To</label>
									<input class="assigned-select form-control" name="staff_id" placeholder="Leave empty for any">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1" class="control-label">Department</label>
									<input class="dept-select form-control" name="dept_id" placeholder="Leave empty for any">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<label>Date Range [Create Date]:</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
									<input type="text" class="form-control pull-right" id="createtime" name="created_at" placeholder="Leave empty for all"/>
								</div><!-- /.input group -->
							</div>
						</div>

					</div>
					<div class="modal-footer clearfix">

						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
					</div>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</section><!-- /.modal -->
@stop