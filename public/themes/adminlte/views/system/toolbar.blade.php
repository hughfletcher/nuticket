<div class="row">
	<div class="col-md-12">
		<div class="btn-toolbar">
			<div class="btn-group">
				<div class="dropdown">
				  	<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    {{ $current }}
				    <span class="caret"></span>
				  	</button>
				  	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				  		<li><a href="{{ route('system.logs.index', ['date' => Carbon\Carbon::now()->tz(user('tz'))->toDateString()]) }}">{{ Carbon\Carbon::now()->tz(user('tz'))->toDateString() }}</a></li>
				  		@for ($i = 1; $i <= config('app.log_max_files'); $i++)
				    	<li><a href="{{ route('system.logs.index', ['date' => Carbon\Carbon::now()->tz(user('tz'))->subDay($i)->toDateString()]) }}">{{ Carbon\Carbon::now()->tz(user('tz'))->subDay($i)->toDateString() }}</a></li>
				    	@endfor
				  </ul>
				</div>
			</div>
			<div class="btn-group">
				<div class="dropdown">
				  	<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    {{ Request::has('level') ? trans('system.filter_' . Request::get('level')) : trans('system.show_all_levels') }}
				    <span class="caret"></span>
				  	</button>
				  	<ul class="dropdown-menu">
				  		<li><a href="{{ route('system.logs.index', ['date' => $current]) }}">{{ trans('system.show_all_levels') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'debug']) }}">{{ trans('system.filter_debug') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'info']) }}">{{ trans('system.filter_info') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'notice']) }}">{{ trans('system.filter_notice') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'warning']) }}">{{ trans('system.filter_warning') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'error']) }}">{{ trans('system.filter_error') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'critical']) }}">{{ trans('system.filter_critical') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'alert']) }}">{{ trans('system.filter_alert') }}</a></li>
				  		<li><a href="{{ route('system.logs.index', ['date' => $current, 'level' => 'emergency']) }}">{{ trans('system.filter_emergency') }}</a></li>
				  </ul>
				</div>
			</div>
			<span class="text-aqua pull-right"><strong>{{ trans('system.all_times_in_utc') }}</strong></span>
		</div>
	</div>
</div>