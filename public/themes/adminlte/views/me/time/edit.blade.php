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
                <h3 class="box-title">Edit Entry</h3>
            </div><!-- /.box-header -->
            <form method="POST" action="{{ route('me.time.update', $entry->id) }}" accept-charset="UTF-8"> 
            <input name="_method" type="hidden" value="PUT">
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <div class="box-body">
            	<div class="row">
            		<div class="col-md-2">
		            	<div class="form-group{!! $errors->has('time_at') ? ' has-error' : null !!}">
							<label for="time_at" class="control-label">Date</label>
							<input name="time_at" type="text"  value="{{ old('time_at') ? old('time_at') : date_format($entry->time_at, 'm/d/Y') }}"  class="form-control input-sm singledatedown">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group{!! $errors->has('type') ? ' has-error' : null !!}">
							<label for="type" class="control-label">Type</label>
							<select name="type" class="form-control select2-default input-sm" placeholder="Select a Type"{{ $entry->type == 'action' ? ' disabled' : null }}>
								<option></option>
								<option value="sick"{{ old('type') == 'sick' || $entry->type == 'sick' ? ' selected=selected' : null }}>Sick</option>
								<option value="vacation"{{ old('type') == 'vacation' || $entry->type == 'vacation' ? ' selected=selected' : null }}>Vacation</option>
								<option value="holiday"{{ old('type') == 'holiday' || $entry->type == 'holiday' ? ' selected=selected' : null }}>Holiday</option>
								<option value="other"{{ old('type') == 'other' || $entry->type == 'other' ? ' selected=selected' : null }}>Other</option>
								@if($entry->type == 'action')
								<option selected=selected>#{{ $entry->action->ticket_id }} Ticket</option>
								@endif
							</select>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<label for="hours" class="control-label">Hours</label>
							<input class="form-control input-sm" name="hours" value="{{ old('hours') ? old('hours') : $entry->hours }}">
						</div>
					</div>
					<div class="col-md-7">
						<label>Note</label>
						<div class="form-group">
							<input class="form-control input-sm" name="message" value="{{ old('message') ? old('message') : ( $entry->type == 'action' ? $entry['action']['body'] : $entry->message ) }}"{{ $entry->type == 'action' ? ' disabled' : null }}>
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
                <button type="submit" class="btn btn-default btn-sm">Edit</button>
                <a href="{{ route('me.time.destroy', $entry->id) }}" class="btn btn-danger btn-sm pull-right">Delete</a>
              </div>
        </div>
    </div>
</div>

</section><!-- /.modal -->

@stop