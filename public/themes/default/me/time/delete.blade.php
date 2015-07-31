@extends('layouts.master')

@section('title', 'Tickets')

@section('content')
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
                <h3 class="box-title">Delete Time Entry</h3>
            </div><!-- /.box-header -->
            <form method="POST" action="{{ route('me.time.destroy', $entry->id) }}" accept-charset="UTF-8"> 
            <input name="_method" type="hidden" value="DELETE">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <input name="_redirect" type="hidden" value="{{ URL::previous() }}">
            <div class="box-body">

            	<div class="row">
            		<div class="col-md-2">
		            	<div class="form-group">
							<label for="time_at" class="control-label">Date</label>
							<input type="text"  value="{{ date_format($entry->time_at, 'm/d/Y') }}"  class="form-control input-sm" readonly>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="type" class="control-label">Type</label>
							<input type="text"  value="{{ $entry->type == 'action' ? '#' . $entry->action->ticket_id . ' Ticket Action' : ucfirst($entry->type) }}"  class="form-control input-sm" readonly>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<label for="hours" class="control-label">Hours</label>
							<input class="form-control input-sm" value="{{ $entry->hours }}" readonly>
						</div>
					</div>
					<div class="col-md-7">
						<label>Note</label>
						<div class="form-group">
							<input class="form-control input-sm" value="{{ $entry->type == 'action' ? $entry['action']['body'] : $entry->message  }}" readonly>
						</div><!-- /.input group -->
					</div>
				</div>
				@if ($entry->type == 'action')
				<div class="row">
					<div class="col-md-12">
            	<strong class="text-danger">This will only delete the time associated with your {{ $entry->action->type }} to ticket #{{ $entry->action->ticket_id }}. It will not delete the {{ $entry->action->type }} itself.</strong>
            
					</div>
				</div>
				@endif
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                <a href="{{ URL::previous() }}" class="btn btn-default btn-sm">Cancel</a>
              </div>
        </div>
    </div>
</div>

</section><!-- /.modal -->

@stop