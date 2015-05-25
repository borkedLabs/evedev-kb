<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Controller;

use EDK\Cache\Cache;
use EDK\Core\Event;
use EDK\Page\Page;

/*
 * @package EDK
 */
class Locked extends Base
{
	/** @var Page */
	public $page = null;

	function indexAction()
	{
		$this->queue("start");
		$this->queue("content");
		$this->generate();
		
		Cache::generate();
	}
	
	public function generate()
	{
		Event::call("locked_assembling", $this);
		$html = $this->assemble();
		$this->page->setContent($html);

		$this->page->generate();
	}

	function start()
	{
		$this->page = new Page("Locked");
	}

	function content()
	{
		global $smarty;
		return $smarty->fetch(get_tpl("locked"));
	}
}
