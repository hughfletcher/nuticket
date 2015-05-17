@extends('layouts.master')

@section('title', 'Report - ' . $title)

@section('content')
<section class="content-header">
	<h1>
		{{ $title }}
		<small>Report</small>
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}

</section>

<section class="content">

	@if ($errors->any())
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

								</div>
								<div class="col-sm-6">
								@foreach($header as $var)
									@include('reports.variables.header.' . $var['type'], $var)
								@endforeach
								</div>
							</div><!-- /.row -->

							<div class="box-body table-responsive">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											@foreach($results[0] as $title => $val)
												<td>{{ $title }}</td>
											@endforeach
										</tr>
									</thead>
									
									<tbody>
									@foreach($results as $row)
										<tr>
										@foreach ($row as $val)
											<td>{{ $val }}</td>
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