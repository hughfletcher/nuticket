@extends('layouts.master')

@section('title', 'Tickets')

@section('content')
@include('common.modals.delete')
<section class="content-header">
	<h1>
		Time
		<small>Control panel</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content">
<div class="row">
<div class="col-md-12">
		<div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Add</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
            </div><!-- /.box-header -->
            <form method="POST" action="{{ route('me.time.store') }}" accept-charset="UTF-8">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <input name="_redirect" type="hidden" value="{{ URL::full() }}">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <div class="box-body">
            	<div class="row">
            		<div class="col-md-2">
		            	<div class="form-group{!! $errors->has('time_at') ? ' has-error' : null !!}">
							<label for="time_at" class="control-label">Date</label>
							<input name="time_at" type="text"  value="{{ old('time_at') ? old('time_at') : date('m/d/Y') }}"  class="form-control input-sm singledatedown">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group{!! $errors->has('type') ? ' has-error' : null !!}">
							<label for="type" class="control-label">Type</label>
							<select name="type" class="form-control select2-default input-sm" placeholder="Select a Type">
								<option></option>
								<option value="sick"{{ old('type') == 'sick' ? ' selected=selected' : null }}>Sick</option>
								<option value="vacation"{{ old('type') == 'vacation' ? ' selected=selected' : null }}>Vacation</option>
								<option value="holiday"{{ old('type') == 'holiday' ? ' selected=selected' : null }}>Holiday</option>
								<option value="other"{{ old('type') == 'other' ? ' selected=selected' : null }}>Other</option>
							</select>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<label for="hours" class="control-label">Hours</label>
							<input class="form-control input-sm" name="hours" value="{{ old('hours') }}">
						</div>
					</div>
					<div class="col-md-7">
						<label>Note</label>
						<div class="form-group">
							<input class="form-control input-sm" name="message" value="{{ old('message') }}">
						</div><!-- /.input group -->
					</div>
				</div>
				@if ($errors->any())
				<div class="row">
					<div class="col-md-12">
						<ul class="list-unstyled">
						@foreach ($errors->all() as $error)
							<li class="text-red"><strong>{{ $error }}</strong></li>
						@endforeach
						</ul>
					</div>
				</div>
				@endif
            </div>
            <div class="box-footer">
                {{-- <button type="submit" class="btn btn-default">Cancel</button> --}}
                <button type="submit" class="btn btn-default btn-sm">Add</button>
              </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Log</h3>
                <div class="box-tools pull-right">
                	<ul class="pagination pagination-sm no-margin pull-right">
	                    <li{!! $logs->currentPage() == 1 ? ' class="disabled"' : null !!}><a href="{{ route('me.time.index', ['page' => $logs->currentPage() - 1]) }}"><i class="fa fa-chevron-left"></i></a></li>

	                    <li{!! !$logs->hasMorePages() ? ' class="disabled"' : null !!}><a href="{{ route('me.time.index', ['page' => $logs->currentPage() + 1]) }}"><i class="fa fa-chevron-right"></i></a></li>
	                </ul>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-condensed table-hover no-wrap-col">
                    <tr>
                        <th class="width-120">Date</th>
                        <th class="width-60">Hours</th>
                        <th class="width-100">Type</th>
                        <th>Message</th>
                        <th class="width-100"></th>
                    </tr>
                    @foreach ($logs as $entry)
                    <tr>
                        <td>{{ $entry->time_at->tz(auth()->user()->timezone)->format(config('system.format.dateday')) }}</td>
                        <td>{{ $entry['hours'] }}</td>
                        <td><span class="label label-{{ in_array($entry['type'], ['holiday', 'vacation']) ? 'primary' : ( $entry['type'] == 'sick' ? 'info' : ( $entry['type'] == 'other' ? 'default' : ( !is_null($entry['action']['ticket']['id']) ? 'success' : null))) }}">
                        	{{ !is_null($entry['action']['ticket']['id']) ? '#' . $entry['action']['ticket']['id'] : ucfirst($entry['type']) }}
                        </span></td>
                        <td class="no-wrap">{{ is_null($entry['ticket_action_id']) ? $entry['message'] : $entry['action']['body'] }}</td>
                        <td class="tools">
                        	<div>
                        	<a href="{{ route('me.time.edit', $entry['id']) }}" data-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-wrench"></i></a>
                        	<a href="" data-toggle="tooltip" title="Delete" class="delete" data-id="{{ $entry['id'] }}"><i class="fa fa-times"></i></a>
                        	@if (!is_null($entry->ticket_action_id))
                        	<a href="{{ route('tickets.show', [$entry['action']['ticket']['id'], '#action-' . $entry['action']['id']]) }}" target="_blank" data-toggle="tooltip" title="Go to Ticket"><i class="fa fa-external-link"></i></a>
                        	@endif
                        	</div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="box-footer clearfix">

	        </div>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
</section><!-- /.modal -->

@stop
