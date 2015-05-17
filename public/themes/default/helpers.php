<?php

if ( ! function_exists('menu_icon'))
{
	function menu_icon($string) {
		
		$icons = [
			'Tickets' => 'ticket',
			'Reports' => 'book',
			'Development' => 'flask'
		];

		return $icons[$string];

	}
}

if ( ! function_exists('config'))
{
	function config($string, $default = null) {
		
		return $default;

	}
}

if ( ! function_exists('css'))
{
	function css($path) {
		
		return asset('themes/default/assets/css/' . $path);

	}
}

if ( ! function_exists('js'))
{
	function js($path) {
		
		return asset('themes/default/assets/js/' . $path);

	}
}

if ( ! function_exists('old'))
{
	function old($string) {
		
		return app('request')->old($string);

	}
}

if ( ! function_exists('user'))
{
	function user($string) {
		
		return app('auth')->user()->{$string};

	}
}

if ( ! function_exists('datetime'))
{
	function datetime($time) {
		
		return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $time)->format(config('site.date_time_format', 'm/d/Y g:i a'));
		
	}
}

if ( ! function_exists('sort_url'))
{
	function sort_url($field) {
		
		$query = app('request')->query();
		$order = 'desc';

		if (isset($query['sort']) && $query['sort'] == $field) {

			$order = $query['order'] == 'desc' ? 'asc' : 'desc';

		}

		$query['sort'] = $field;
		$query['order'] = $order;
		

		return route(app('router')->currentRouteName(), array_except($query, ['_url']));
		
	}
}

if ( ! function_exists('order'))
{
	function order($field, $default = null, $prefix = null) {
		
		$query = app('request')->query();
		if (isset($query['sort']) && $query['sort']== $field) {
			return $prefix . $query['order'];
		}
		
		return $default;
	}
}

if ( ! function_exists('array_json'))
{
	function array_json($array) {

		$json = [];
		foreach ($array as $key => $value) {
			$json[] = ['id' => $key, 'text' => $value];
		}

		return json_encode($json);
	}
}


