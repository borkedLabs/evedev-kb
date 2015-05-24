<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\PageComponent;

use EDK\Core\Config;
use EDK\Core\Language;
use EDK\Core\URI;
use EDK\Database;
use \Killboard;
/**
 * @package EDK
 */
class Navigation
{
	private $type = 'top';
	private $page = '';
	private $site = null;
	private	$qry = null;

	function __construct($site = KB_SITE)
	{
		$this->site = $site;
	}

	private function execQuery()
	{
		$this->qry = Database\Factory::getDBQuery();
		$query = "SELECT * FROM kb3_navigation".
			" WHERE nav_type = '$this->type'";

		$query .= " AND url NOT LIKE '%?a=contracts'";

		if (Killboard::hasCampaigns() == false)
		{
			$query .= " AND url NOT LIKE '%?a=campaigns'";
			$query .= " AND url NOT LIKE '%/campaigns/'";
		}
		if (Config::get('public_losses'))
		{
			$query .= " AND url NOT LIKE '%?a=losses'";
			$query .= " AND url NOT LIKE '%/losses/'";
		}
		if (!Config::get('show_standings'))
		{
			$query .= " AND url NOT LIKE '%a=standings'";
			$query .= " AND url NOT LIKE '%/standings/'";
		}
		if (Config::get('public_stats')=='remove')
		{
			$query .= " AND url NOT LIKE '%?a=self_detail'";
			$query .= " AND url NOT LIKE '%/self_detail/'";
		}
		$query .= " AND (page = '".$this->page."' OR page = 'ALL_PAGES') AND hidden = 0";
		$query .= " AND KBSITE = '" . $this->site . "' ORDER BY posnr";
		$this->qry->execute($query);

		// If no navigation table was found then make one.
		if(!$this->qry->recordCount())
		{
			$this->check_navigationtable();
			$this->qry->execute($query);
		}
	}

	private function getRow()
	{
		return $this->qry->getRow();
	}

	public function setNavType($type)
	{
		$this->type = $type;
	}

	public function generateMenu()
	{
		$this->execQuery();

		$menu = new Menu();
		while ($row = $this->getRow()) {
			$url = $row['url'];
			// Note that changing the standard naming will also remove any
			// translations.
			$menu->add($url , Language::get($row['descr']));
		}
		return $menu;
	}

	private function check_navigationtable()
	{
		$sql = "select count(KBSITE) as cnt from kb3_navigation WHERE KBSITE = '".$this->site."'";
		$qry = Database\Factory::getDBQuery(true);
		// return false if query fails
		if(!$qry->execute($sql) || !($row = $qry->getRow())) {
			return false;
		}
		if($row['cnt'] == 0) {
			$this->reset();
		}
	}

	public function reset()
	{
		$sql = "DELETE from kb3_navigation WHERE KBSITE = '".$this->site."'";
		$qry = Database\Factory::getDBQuery(true);
		$qry->autocommit(false);
		$qry->execute($sql);
		// return false if query fails
		if(!$qry->execute($sql)) {
			return false;
		}
		$sql = "INSERT INTO `kb3_navigation` (`nav_type`,`intern`,`descr` ,`url` ,`target`,`posnr`,`page` ,`hidden`,`KBSITE`) VALUES".
			" ('top',1,'Home','".URI::build(array('a', 'home', true))."','_self',1,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Campaigns','".URI::build(array('a', 'campaigns', true))."','_self',2,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Post Kill','".URI::build(array('a', 'post', true))."','_self',3,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Stats','".URI::build(array('a', 'self_detail', true))."','_self',4,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Awards','".URI::build(array('a', 'awards', true))."','_self',5,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Standings','".URI::build(array('a', 'standings', true))."','_self',6,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Search','".URI::build(array('a', 'search', true))."','_self',7,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'Admin','".URI::build(array('a', 'admin', true))."','_self',8,'ALL_PAGES',0,'".$this->site."'),".
			" ('top',1,'About','".URI::build(array('a', 'about', true))."','_self',9,'ALL_PAGES',0,'".$this->site."');";
		$qry->execute($sql);
		$qry->autocommit(true);
		return true;
	}
}
