<?php

if ( ! function_exists('menu_icon'))
{
	function menu_icon($string) {

		$icons = [
			'Tickets' => 'ticket',
			'Reports' => 'book',
			'Change Log' => 'flask',
            'settings.settings' => 'gear',
            'system.system' => 'rocket'
		];

		return $icons[$string];

	}
}

if ( ! function_exists('user'))
{
	function user($string = 'id') {

		if (!app('auth')->check()) {
			return null;
		}

		return app('auth')->user()->{$string};

	}
}

if ( ! function_exists('cached_asset'))
{
    function cached_asset($path)
    {
        // Get the full path to the asset.
        $realPath = public_path('themes/adminlte/' . $path);

        if ( ! file_exists($realPath)) {
            throw new LogicException("File not found at [{$realPath}]");
        }

        // Get the last updated timestamp of the file.
        $timestamp = filemtime($realPath);

            // Append the timestamp to the path as a query string.
        $path  .= '?' . $timestamp;

        return asset('themes/adminlte/' . $path);
    }
}

// if ( ! function_exists('datetime'))
// {
// 	function datetime($time) {

// 		return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $time)->format(config('site.date_time_format', 'm/d/Y g:i a'));

// 	}
// }

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
if ( ! function_exists('parse_links'))
{
    function parse_links($string, $limit = 40) {
        return preg_replace_callback('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', function($matches) use ($limit) {
            return '<a href="'. $matches[1] . '" target="_blank">' . str_limit($matches[1], $limit) . '</a>';
        }, $string);
    }
}
