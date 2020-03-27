<?php

if (function_exists('ini_set')) {
	ini_set('display_errors', false);
	ini_set('log_errors', true);
}

if (!isset($_SERVER['REMOTE_USER'])) {
	if (isset($_SERVER['PHP_AUTH_USER']))
		$_SERVER['REMOTE_USER'] = $_SERVER['PHP_AUTH_USER'];
	else
	if (isset($_SERVER['REDIRECT_REMOTE_USER']))
		$_SERVER['REMOTE_USER'] = $_SERVER['REDIRECT_REMOTE_USER'];
}

if (!isset($locale))
	$locale = "UTF8";

setlocale(LC_CTYPE, $locale, "UTF-8", "en_US.UTF-8", "en_US.UTF8");
setlocale(LC_COLLATE, $locale, "UTF-8", "en_US.UTF-8", "en_US.UTF8");

function getLogin()
{
	return ((isset($_SERVER['REMOTE_USER']) && !empty($_SERVER['REMOTE_USER'])) ? strtolower($_SERVER['REMOTE_USER']) : '');
}

function getUser()
{
	global $forbidUserSettings;
	return (!$forbidUserSettings ? getLogin() : '');
}

@ini_set('precision', 16);
@define('PHP_INT_MIN', ~PHP_INT_MAX);
@define('XMLRPC_MAX_I4', 2147483647);
@define('XMLRPC_MIN_I4', ~XMLRPC_MAX_I4);
@define('XMLRPC_MIN_I8', -9.999999999999999E+15);
@define('XMLRPC_MAX_I8', 9.999999999999999E+15);