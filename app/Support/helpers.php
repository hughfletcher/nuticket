<?php

function lg($level = null, $msg = null, $context = [])
{
	if (!$level) {
		return app('log');
	}
	
	app('log')->{$level}($msg, array_merge($context, ['file' => __FILE__, 'line' => __LINE__]));
}

function debug($msg, $context = []) { app('log')->debug($msg, $context);}

function version() {
	if (is_file(base_path() . '/.git/shallow')) {
		return '1.0.0-alpha ' . substr(trim(file_get_contents(base_path() . '/.git/shallow')), 0, 7);
	}
	return '1.0.0-dev';
}