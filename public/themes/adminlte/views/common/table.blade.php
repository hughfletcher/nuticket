<div class="box-body">
	<table id="example2" class="table table-bordered table-hover no-wrap-col">
		<thead>
			<tr>
			@foreach($columns as $key => $value)
				<th{!! isset($th[$key]) ? ' ' . $th[$key] : '' !!}>
					<a href="{{ sort_url($key) }}">{{ trans($value) }}<span><i class="fa fa-fw fa-sort{{ order($key, null, '-') }}"></i></span></a>
					
				</th>
			@endforeach
			</tr>
		</thead>

		<tbody>
			@if ($data->count() >= 1)
			@foreach ($data as $row)
			<tr>
				@foreach($row as $key => $value)
				@if(!array_key_exists($key, $columns))
				@continue
				@endif
				<td{!! isset($td[$key]) ? ' ' . $td[$key] : '' !!}>
				@if(isset($format[$key]))
					{!! $format[$key]($row) !!}
				@else
				{{ $value }}
				@endif
				</td>
				@endforeach
			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
			@foreach($columns as $key => $value)
				<th{!! isset($th[$key]) ? ' ' . $th[$key] : '' !!}>
					<a href="{{ sort_url($key) }}">{{ trans($value) }}<span><i class="fa fa-fw fa-sort{{ order($key, null, '-') }}"></i></span></a>
					
				</th>
			@endforeach
			</tr>
		</tfoot>
		@else
		<tr>
			<td colspan="7" class="text-center">There is no data to view</td>
		</tr>

	@endif

	</table>

	<div class="row">
		<div class="col-xs-4">
			<div class="table_info" id="example2_info">{{ trans($info, ['start' => $data->firstItem(), 'last' => $data->lastItem(), 'total' => $data->total()]) }}</div>
		</div>
		<div class="col-xs-8">
			<div class="table_paginate paging_bootstrap">
				{!! $data->appends(Request::query())->render() !!}
			</div>
		</div>
	</div>
</div>
