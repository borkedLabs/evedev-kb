<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\Page;

use EDK\Core\Config;
use EDK\Core\Event;
use EDK\Core\URI;
use EDK\Killmail;
use EDK\PageComponent\Box;

/*
 * @package EDK
 */
class SystemDetail extends \pageAssembly
{
	/** @var Page */
	public $page = null;
	/** @var integer */
	public $sys_id = 0;

	/** @var System */
	protected $system;
	/** @var string The selected view. */
	protected $view = null;
	/** @var array The list of views and their callbacks. */
	protected $viewList = array();
	/** @var array The list of menu options to display. */
	protected $menuOptions = array();

	/** @var KillSummaryTable */
	private $kill_summary = null;

	function __construct()
	{
		parent::__construct();
		$this->queue("start");
		$this->queue("map");
		$this->queue("statSetup");
		$this->queue("summaryTable");
		$this->queue("killList");
	}


	public function generate()
	{
		Event::call("systemdetail_assembling", $this);
		$html = $this->assemble();
		$this->page->setContent($html);

		$this->context();
		Event::call("systemdetail_context_assembling", $this);
		$context = $this->assemble();
		$this->page->addContext($context);

		$this->page->generate();
	}
	
	/**

	 * Start constructing the page.

	 * Prepare all the shared variables such as dates and check alliance ID.
	 *
	 */
	function start()
	{
		$this->sys_id = (int)URI::getArg('sys_id', 1, true);
		$this->view = preg_replace('/[^a-zA-Z0-9_-]/', '',
						URI::getArg('view', 2, true));

		global $smarty;
		$this->smarty = $smarty;
		$this->viewList = array();
		$this->menuOptions = array();

		$this->page = new Page();
		$this->page->addHeader('<meta name="robots" content="noindex, nofollow" />');

		if (!$this->sys_id) {
			echo 'no valid id supplied<br/>';
			exit;
		}

		$this->page->addHeader("<link rel='canonical' href='".
				URI::build($this->args)."' />");

		$this->system = new \SolarSystem($this->sys_id);
		$this->menuOptions = array();
		$this->page->setTitle('System details - '.$this->system->getName());
		$this->smarty->assign('sys_id', $this->sys_id);
	}

	function map()
	{
		return $this->smarty->fetch(get_tpl("system_detail_map"));
	}

	/**
	 *  Set up the stats used by the stats and summary table functions
	 */
	function statSetup()
	{
		$this->kill_summary = new \KillSummaryTable();
		$this->kill_summary->setSystem($this->sys_id);
		if (Config::get('kill_classified')) {
			$this->kill_summary->setEndDate(
					gmdate('Y-m-d H:i', strtotime('now - '
					.(Config::get('kill_classified')).' hours')));
		}
		\involved::load($this->kill_summary, 'kill');
		$this->kill_summary->generate();
		return "";
	}

	/**
	 *  Build the summary table showing all kills and losses for this corporation.
	 */
	function summaryTable()
	{
		if ($this->view != '' && $this->view != 'kills'
				&& $this->view != 'losses') {
			return '';
		}
		return $this->kill_summary->generate();
	}

	/**
	 *  Build the killlists that are needed for the options selected.
	 */
	function killList()
	{
		global $smarty;
		if (isset($this->viewList[$this->view])) {
			return call_user_func_array(
					$this->viewList[$this->view], array(&$this));
		}
		$scl_id = (int)URI::getArg('scl_id');

		$klist = new Killmail\Collection();
		$klist->setOrdered(true);
		if ($this->view == 'losses') {
			\involved::load($klist, 'loss');
		} else {
			\involved::load($klist, 'kill');
		}
		$klist->addSystem($this->system);
		if (Config::get('kill_classified')) {
			$klist->setEndDate(gmdate('Y-m-d H:i', strtotime('now - '
					.(Config::get('kill_classified')).' hours')));
		}
		if ($scl_id) {
			$klist->addVictimShipClass(intval($scl_id));
		} else {
			$klist->setPodsNoobShips(Config::get('podnoobs'));
		}

		if ($this->view == 'recent' || !$this->view) {
			$klist->setLimit(20);
			$smarty->assign('klheader', Config::get('killcount').' most recent kills');
		} else if ($this->view == 'losses') {
			$smarty->assign('klheader', 'All losses');
		} else {
			$smarty->assign('klheader', 'All kills');
		}

		$klist->setPageSplit(Config::get('killcount'));

		$pagesplitter = new \PageSplitter($klist->getCount(), Config::get('killcount'));

		$table = new \KillListTable($klist);
		$smarty->assign('klsplit', $pagesplitter->generate());
		$smarty->assign('kltable', $table->generate());
		$html = $smarty->fetch(get_tpl('system_detail'));

		return $html;
	}

	/**
	 *  Reset the assembly object to prepare for creating the context.
	 */
	function context()
	{
		parent::__construct();
		$this->queue("menuSetup");
		$this->queue("menu");
	}

	/**
	 * Set up the menu.
	 *
	 *  Prepare all the base menu options.
	 */
	function menuSetup()
	{
		$args = array();
		$args[] = array('a', 'system_detail', true);
		$args[] = array('sys_id', $this->sys_id, true);
		$this->addMenuItem("caption", "Navigation");
		$this->addMenuItem("link", "All kills",
				URI::build($args, array('view', 'kills', true)));
		$this->addMenuItem("link", "All losses",
				URI::build($args, array('view', 'losses', true)));
		$this->addMenuItem("link", "Recent Activity",
				URI::build($args, array('view', 'recent', true)));
		return "";
	}

	/**
	 * Build the menu.
	 *
	 *  Add all preset options to the menu.
	 */
	function menu()
	{
		$menubox = new Box("Menu");
		$menubox->setIcon("menu-item.gif");
		foreach ($this->menuOptions as $options) {
			if (isset($options[2]))
					$menubox->addOption($options[0], $options[1], $options[2]);
			else $menubox->addOption($options[0], $options[1]);
		}
		return $menubox->generate();
	}

	/**
	 * Add an item to the menu in standard box format.
	 *
	 *  Only links need all 3 attributes
	 * @param string $type Types can be caption, img, link, points.
	 * @param string $name The name to display.
	 * @param string $url Only needed for URLs.
	 */
	function addMenuItem($type, $name, $url = '')
	{
		$this->menuOptions[] = array($type, $name, $url);
	}

	/**

	 * Add a type of view to the options.

	 *
	 * @param string $view The name of the view to recognise.
	 * @param mixed $callback The method to call when this view is used.
	 */
	function addView($view, $callback)
	{
		$this->viewList[$view] = $callback;
	}

	/**
	 * Return the set view.
	 * @return string
	 */
	function getView()
	{
		return $this->view;
	}
}