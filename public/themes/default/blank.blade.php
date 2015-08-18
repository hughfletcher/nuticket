@extends('layouts.master')

@section('content')
@if (isset($title))
<section class="content-header">
	<h1>
		{{ $title }}
	</h1>
</section>
@endif

<!-- Main content -->
<section class="content">
	{!! $content !!}
</section><!-- /.modal -->
@stop