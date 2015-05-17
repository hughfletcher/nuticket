@extends('layouts.master')

@section('title', 'Report - ' . $report['name'])

@section('content')
<section class="content-header">
	<h1>
		{{ $report['name'] }}
		<small>Report</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}

</section>

<section class="content">

	<div class="mailbox row">
		<div class="col-xs-12">
			<div class="box box-solid">
				<div class="box-body">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							{{-- <div class="row pad">
								<div class="col-sm-6">

								</div>
								<div class="col-sm-6">
								@foreach($header as $var)
									@include('reports.variables.header.' . $var['type'], $var)
								@endforeach
								</div>
							</div> --}}<!-- /.row -->

							<div class="box-body table-responsive">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											@foreach($results[0] as $title => $val)
												@if (strpos($title, 'hide_') !== 0)
												<td>{{ $title }}</td>
												@endif
											@endforeach
										</tr>
									</thead>
									
									<tbody>
									@foreach($results as $row)
										<tr>
										@foreach ($row as $key => $val)
											@if (strpos($key, 'hide_') !== 0)
											<td>{{ $val }}</td>
											@endif
										@endforeach
										</tr>
									@endforeach
									</tbody>
									<tfoot>
			
									</tfoot>
								


								</table>
								<div class="row">
									
								</div>
							</div>

						</div><!-- /.col (RIGHT) -->
					</div><!-- /.row -->
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div><!-- /.col (MAIN) -->
	</div>
</section>
@stop