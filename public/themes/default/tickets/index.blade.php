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
											<td><a href="{{ route('tickets.show', [$ticket['id'], '#action']) }}">{{ $ticket['id'] }}</a></td>
											<td>{{ datetime($ticket['last_action_at']) }}</td>
											<td><a href="{{ route('tickets.show', [$ticket['id']]) }}">{{ $ticket['subject'] }}</a></td>
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


@stop