<?php

use EDK\Core\Config;

include_once(__DIR__.'/../kbconfig.php');
	
require_once(__DIR__.'/SplClassLoader.php');
$loader = new SplClassLoader('EDK', realpath(__DIR__.'/../'));
$loader->register();

require_once (__DIR__.'/../common/includes/class.edkerror.php');
set_error_handler(array('EDKError', 'handler'), E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE) );
@error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE));


require_once(__DIR__ . '/../common/includes/globals.php');

include_once(__DIR__.'/constants.php');

// Make sure the core functions are loaded.
require_once(__DIR__.'/../common/includes/class.edkloader.php');
spl_autoload_register('edkloader::load');


// Ugly hacks to make things work until other changes are made with the file structure
edkloader::register('thumbInt', 'class.thumb.php');

require_once(__DIR__.'/../common/includes/db.php');