@extends('layouts.master')

@section('content')
<section class="content-header">
	<h1>
		{{ $title }}
	</h1>
</section>

<!-- Main content -->
<section class="content">
	{{ $content }}
</section><!-- /.modal -->
@stop