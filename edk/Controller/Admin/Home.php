<?php
/*
 * @package EDK
 */

namespace EDK\Controller\Admin;

use EDK\Core\Event;
use EDK\Core\Language;
use EDK\Core\URI;
use EDK\Cache\Cache;
use EDK\Database;
use EDK\Page\Page;
use EDK\Page\Component\Options;

class Home extends Base
{
	/** @var Page The Page object used to display this page. */
	public $page;

	function indexAction()
	{
		$page = new Page();
		$page->setAdmin();

		if ($_POST) 
		{
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
	}
}