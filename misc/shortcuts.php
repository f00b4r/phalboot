<?php

use Phalcon\Debug\Dump as PhDump;
use Tracy\Debugger;

function pd()
{
	echo call_user_func_array([new PhDump(), 'variables'], func_get_args());
}

function pdd()
{
	echo call_user_func_array([new PhDump(), 'variables'], func_get_args());
	die();
}

function pjd()
{

	echo call_user_func_array([new PhDump(), 'toJson'], func_get_args());
	die();
}

function d()
{
	Debugger::$maxDepth = 10;
	foreach (func_get_args() as $var) {
		Debugger::dump($var);
	}
}

function dd()
{
	Debugger::$maxDepth = 10;
	foreach (func_get_args() as $var) {
		Debugger::dump($var);
	}
	die;
}
