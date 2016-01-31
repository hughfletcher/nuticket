@extends('layouts.master')

@section('title', 'Reports')

@section('content')
<section class="content-header">
	<h1>
		Reports
		{{-- <small>Report</small> --}}
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}

</section>

<section class="content reports">

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
								<table id="example2" class="table table-hover">
									<thead>
										<tr>
											<td>Name</td>
											<td>Description</td>
											<td></td>
										</tr>
									</thead>
									
									<tbody>
										@foreach($reports as $row)
										<tr>
											<td>{{ $row['name'] }}</td>
											<td>{{ $row['desc'] }}</td>
											<td class="action">
												<a href="{{ route('reports.show', [$row['id']]) }}" data-toggle="tooltip" data-placement="top" title="Run">
													<i class="fa fa-chevron-circle-right fa-lg"></i>
												</a>
											</td>
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