<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

use EDK\Core\Session;

$session = new Session();
$session->destroy();
header('Location: '.html_entity_decode(edkURI::page("login")));