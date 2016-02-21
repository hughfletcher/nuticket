<?php

function lg($level = null, $msg = null, $context = [])
{
	if (!$level) {
		return app('log');
	}
	
	app('log')->{$level}($msg, $context);
}

function log_info($msg, $context = []) { app('log')->info($msg, $context);}