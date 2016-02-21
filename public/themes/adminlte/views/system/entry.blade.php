@extends('layouts.master')

@section('title', 'Logs')

@section('content')
<section class="content-header">
	<h1>
		{{ trans('system.log') }}
		<small>{{ trans('system.entry') }}</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content">
	@include('common.error')
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ trans('system.info') }}</h3>
				</div>
				<div class="box-body">
					<dl class="dl-horizontal">
					  	<dt>{{ trans('system.date') }}</dt>
					  	<dd>{{ $log['date'] }}</dd>
					  	<dt>{{ trans('system.level') }}</dt>
					  	<dd>{{ $log['level'] }}</dd>
					</dl>
				</div>
				<div class="box-header">
					<h3 class="box-title">{{ trans('system.entry') }}</h3>
				</div>
				<div class="box-body">
					<div class="box-body">
						<code>{{ $log['message'] }}</code>
					</div><!-- /.col (RIGHT) -->
				</div>
				@if($log['context'])
				<div class="box-header">
					<h3 class="box-title">{{ trans('system.context') }}</h3>
				</div>
				<div class="box-body">
					<div class="box-body">
						<pre>{{ print_r(json_decode($log['context'])) }}</pre>
					</div><!-- /.col (RIGHT) -->
				</div>
				@endif
				@if($log['stack'] != "\n")
				<div class="box-header">
					<h3 class="box-title">{{ trans('system.stack') }}</h3>
				</div>
				<div class="box-body">
					<div class="box-body">
						<code>{{ $log['stack'] }}</code>
					</div><!-- /.col (RIGHT) -->
				</div>
				<div class="box-footer">
	                <a href="{{ URL::previous() }}" class="btn btn-primary">{{ trans('common.back') }}</a>
	              </div>
				@endif
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div><!-- /.col (MAIN) -->

</section>	

@stop