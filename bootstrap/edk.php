<?php

use EDK\Core\Config;

/* Composer provides autoloading for us */
require __DIR__.'/../vendor/autoload.php';

include_once(__DIR__.'/../kbconfig.php');

@error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();



require_once(__DIR__ . '/../common/includes/globals.php');

include_once(__DIR__.'/constants.php');

// Make sure the core functions are loaded.
require_once(__DIR__.'/../common/includes/class.edkloader.php');
spl_autoload_register('edkloader::load');


// Ugly hacks to make things work until other changes are made with the file structure
edkloader::register('thumbInt', 'class.thumb.php');

require_once(__DIR__.'/../common/includes/db.php');