<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Page;
/*
 * @package EDK
 */
class Locked extends \pageAssembly
{
	/** @var Page */
	public $page = null;

	function __construct()
	{
		parent::__construct();

		$this->queue("start");
		$this->queue("content");
	}
	
	public function generate()
	{
		\event::call("locked_assembling", $this);
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
