<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
// admin menu now loads all admin pages with options
require_once('common/admin/admin_menu.php');

use EDK\PageComponent\Options;

use EDK\Page\Page;
use EDK\Core\URI;

$page = new Page();
$page->setAdmin();

if ($_POST) {
    Options::handlePost();
}

$page->setContent(Options::genOptionsPage());

$page->addContext(Options::genAdminMenu());

// reload in order to correctly update the owner removal lists
if ($_POST) {
    admin_config::reload();
    exit();
}

if (!URI::getArg('field', 1)
		|| !URI::getArg('sub', 1)
		|| URI::getArg('field', 1) == 'Advanced'
				&& URI::getArg('sub', 2) == 'Configuration') {
	$page->setTitle('Administration - Board Configuration (Current version: '
			.KB_VERSION.' '.KB_RELEASE.')');
}

$page->generate();

