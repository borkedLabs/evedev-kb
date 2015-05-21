<?php

require __DIR__.'/vendor/autoload.php';
/**
 * Request forwarder, look at common/index.php for the action and license
 * @package EDK
 */

// Enable custom error handling.
require_once(__DIR__ . "/bootstrap/SplClassLoader.php");
$loader = new SplClassLoader('EDK', '');
$loader->setIncludePath(__DIR__);
$loader->register();

require_once (__DIR__.'/common/includes/class.edkerror.php');

	
set_error_handler(array('EDKError', 'handler'), E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE) );
@error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE));

// Party time!
include(__DIR__.'/common/index.php');