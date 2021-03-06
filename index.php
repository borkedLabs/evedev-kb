<?php

require __DIR__.'/vendor/autoload.php';
/**
 * Request forwarder, look at common/index.php for the action and license
 * @package EDK
 */

// Enable custom error handling.
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

@error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE));
date_default_timezone_set("UTC");

// Party time!
include(__DIR__.'/common/index.php');