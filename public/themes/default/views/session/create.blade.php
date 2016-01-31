@extends('layouts.master')

@section('title', trans('settings.system'))

@section('content')
<section class="content-header">
	<h1>
		{{ trans('session.letsgetstarted') }}
		{{-- <small>{{ trans('settings.settings') }}</small> --}}
	</h1>
	{{-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> --}}
</section>

<!-- Main content -->
<section class="content session-create">
    @if(session('message'))
    <div class="form-group">
        <div class="callout callout-danger">
            <p>{{ session('message') }}</p>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <form action="{{ route('session.store') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans('session.signin') }}</h3>
                    </div>
                        <div class="box-body">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="username" class="col-sm-2 control-label">{{ trans('session.username') }}</label>
                                <div class="col-sm-6">
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{ trans('session.username') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">{{ trans('session.password') }}</label>
                                <div class="col-sm-6">
                                    <input type="password" name="password" class="form-control" placeholder="{{ trans('session.password') }}"/>
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button class="btn btn-primary">{{ trans('session.signmein') }}</button>
                        </div>
                </form>
            </div>
        </div>
        @if(config('settings.registration.method') == 'public')
        <div class="col-md-6">
            <div class="box box-success">
                <form action="{{ route('session.store') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
            		<div class="box-header">
            			<h3 class="box-title">{{ trans('session.register') }}</h3>
            		</div>
                		<div class="box-body">
                			<input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="username" class="col-sm-2 control-label">{{ trans('session.email') }}</label>
                                <div class="col-sm-6">
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{ trans('session.username') }}"/>
                                </div>
                            </div>
            				<div class="form-group">
            					<label for="username" class="col-sm-2 control-label">{{ trans('session.username') }}</label>
            					<div class="col-sm-6">
            						<input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="{{ trans('session.username') }}"/>
            					</div>
            				</div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">{{ trans('session.password') }}</label>
                                <div class="col-sm-6">
                                    <input type="password" name="password" class="form-control" placeholder="{{ trans('session.password') }}"/>
                                </div>
                            </div>
            				<div class="form-group">
            					<label for="password" class="col-sm-2 control-label">{{ trans('session.password') }}</label>
            					<div class="col-sm-6">
            						<input type="password" name="password" class="form-control" placeholder="{{ trans('session.password') }}"/>
            					</div>
            				</div>

                		</div><!-- /.box-body -->
                        <div class="box-footer">
                			<button class="btn btn-primary">{{ trans('session.signmein') }}</button>
                		</div>
                </form>
        	</div>
        </div>
        @endif
    </div>
</section>

@stop
