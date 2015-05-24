#!/usr/bin/env php
<?php
set_time_limit(0);

require __DIR__.'/vendor/autoload.php';
$cronStartTime = microtime(true);

@error_reporting(E_ALL ^ E_NOTICE);

require_once __DIR__.'/kbconfig.php';
require_once __DIR__.'/common/includes/globals.php';
require_once __DIR__.'/common/includes/db.php';

// load mods
loadMods();

$consoleArgs = isset($_SERVER['argv']) ? $_SERVER['argv'] : null;
if( $consoleArgs == null || count($consoleArgs) < 2 )
{
	println("Missing command line arguments");
	exit(1);
}

$task = $argv[1];

$tasks = array('api', 
				'cache', 
				'clearup',
				'feed',
				'value',
				'zkb',
				);
				
if( in_array($task, $tasks) )
{
	require_once __DIR__.'/cron/cron_'.$task.'.php';
	
	$name = ucfirst($task).'Command';
	$command = new $name;
	$command->execute();
	
	println("Time taken = ".(microtime(true) - $cronStartTime)." seconds.");
}

function println($msg)
{
	print $msg.PHP_EOL;
}