@extends('layouts.master')

@section('title', 'Logs')

@section('content')
<section class="content-header">
	<h1>
		{{ trans('system.logs') }}
		<small>{{ trans('system.system') }}</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content">
	@include('common.error')
	<div class="mailbox row">
		<div class="col-xs-12">
			<div class="box box-solid">
				<div class="box-body">
					@include('system.toolbar')
					<div class="box-body">
						@include('common.table', [
							'data' => $logs,
							'columns' => [
								'date' => 'system.date',
								'level' => 'system.level',
								'message' => 'system.message'
							],
							'format' => [
								'level' => function($row) {
									$levels = [
										'debug' => ['bug', 'success'],
										'info' => ['lightbulb-o', 'muted'], 
										'notice' => ['flag', 'info'],
										'warning' => ['exclamation', 'warning'],
										'error' => ['exclamation-triangle', 'warning'],
										'critical' => ['', 'danger'],
										'alert' => ['bell', 'danger'],
										'emergency' => ['heartbeat', 'danger']
									];
									return '<span class="text-' . $levels[$row['level']][1] . '"><i class="fa fa-' . $levels[$row['level']][0] . '"></i> ' . $row['level'] . '</span>';
								},
								'message' => function($row) {
									return '<a href="' . route('system.logs.show', [$row['id'], 'date=' . $row['date']->format('Y-m-d')]) . '">' . $row['message'] . '</a>';
								}
							],
							'th' => [
								'date' => 'width="135px"',
								'level' => 'width="80px"',
							],
							'td' => [
								'message' => 'class="no-wrap"'
							],
							'info' => 'system.showing_to_of_entries'
						])
					</div><!-- /.col (RIGHT) -->
				</div><!-- /.row -->
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div><!-- /.col (MAIN) -->

</section>	

@stop