<?php

function lg($level = null, $msg = null, $context = [])
{
	if (!$level) {
		return app('log');
	}
	
	app('log')->{$level}($msg, array_merge($context, ['file' => __FILE__, 'line' => __LINE__]));
}

function debug($msg, $context = []) { app('log')->debug($msg, $context);}
// function info($msg, $context = []) { app('log')->info($msg, $context);}