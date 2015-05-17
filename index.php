<?php

require __DIR__.'/vendor/autoload.php';
/**
 * Request forwarder, look at common/index.php for the action and license
 * @package EDK
 */

// Enable custom error handling.
require_once ('common/includes/class.edkerror.php');

set_error_handler(array('EDKError', 'handler'), E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE) );
@error_reporting(E_ALL & ~(E_STRICT | E_NOTICE | E_USER_NOTICE));

// Set up include paths.
@set_include_path(get_include_path() . PATH_SEPARATOR . '.' . PATH_SEPARATOR . 'common' . PATH_SEPARATOR . 'includes');

// Party time!
include('common/index.php');