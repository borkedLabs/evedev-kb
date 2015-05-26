<?php
/*
 * @package EDK
 */
 
namespace EDK\Controller\Admin;

use EDK\Core\Config;
use EDK\Core\Event;
use EDK\Core\Language;
use EDK\Core\URI;
use EDK\Cache\Cache;
use EDK\Database;
use EDK\Page\Page;
use EDK\Page\Component;
use EDK\Page\Component\Options;

class Navigation extends Base
{
	/** @var Page The Page object used to display this page. */
	public $page;

	function indexAction()
	{
		global $smarty, $menubox;

		$page = new Page();
		$page->setAdmin();
		$page->setTitle('Administration - Navigation - Top Navigation');
		if ($_GET['incPrio'])
		{
			$this->increasePriority($_GET['incPrio']);
		}
		else if ($_GET['decPrio'])
		{
			$this->decreasePriority($_GET['decPrio']);
		}
		else if ($_POST) 
		{
			if ($_POST['add'])
			{
				$this->newPage($_POST['newname'], $_POST['newurl'], '_self');
			}
			else if ($_POST['rename'])
			{
				$id = array_search('rename', $_POST['rename']);
				$this->renamePage($id, $_POST['name'][$id]);
			}
			else if ($_POST['change'])
			{
				$id = array_search('change', $_POST['change']);
				$this->changeUrl($id, $_POST['url'][$id]);
			}
			else if ($_POST['delete'])
			{
				$id = array_search('delete', $_POST['delete']);
				$this->delPage($id);
			}
			else if ($_POST['hide'])
			{
				$id = array_search('hide', $_POST['hide']);
				$this->chgHideStatus($id, 1);
			}
			else if ($_POST['show'])
			{
				$id = array_search('show', $_POST['show']);
				$this->chgHideStatus($id, 0);
			}
			else if ($_POST['reset'])
			{
				$nav = new Component\Navigation();
				$nav->reset();
			}
		}
		$qry = Database\Factory::getDBQuery(true);
		$query = "select * from kb3_navigation WHERE intern = 1 AND KBSITE = '".KB_SITE."' AND descr <> 'About';";

		$internal = array();
		if ($qry->execute($query))
		{
			while ($row = $qry->getRow())
			{
				$internal[] = array('id'=>$row['ID'], 'name'=>$row['descr'], 'hidden'=>$row['hidden']);
			}
		}
		$query = "select * from kb3_navigation WHERE intern = 0 AND KBSITE = '".KB_SITE."';";

		$external = array();
		if ($qry->execute($query))
		{
			while ($row = $qry->getRow())
			{
				$external[] = array('id'=>$row['ID'], 'name'=>$row['descr'], 'url'=>$row['url']);
			}
		}

		$all = array();
		$query = "select * from kb3_navigation WHERE nav_type = 'top' AND KBSITE = '".KB_SITE."' ORDER BY posnr ;";
		if ($qry->execute($query))
		{
			while ($row = $qry->getRow())
			{
				$all[] = array('id'=>$row['ID'], 'name'=>$row['descr'], 'pos'=>$row['posnr']);
			}
		}

		$smarty->assign('inlinks', $internal);
		$smarty->assign('outlinks', $external);
		$smarty->assign('alllinks', $all);
		$html = $smarty->fetch(get_tpl('admin_navmanager'));

		$page->addContext($menubox->generate());
		$page->setContent($html);
		$page->generate();
	}
	

	function increasePriority($id)
	{
		$id = (int) $id;
		$qry = Database\Factory::getDBQuery(true);
		$qry->autocommit(false);
		$query = "SELECT posnr FROM kb3_navigation WHERE ID = $id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
		$row = $qry->getRow();
		$next = $row['posnr'] + 1;

		$query = "UPDATE kb3_navigation SET posnr = (posnr-1) WHERE nav_type = 'top' AND posnr = $next AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);

		$query = "UPDATE kb3_navigation SET posnr = (posnr+1) WHERE ID = $id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
		$qry->autocommit(true);
	}
	
	function decreasePriority($id)
	{
		$id = (int) $id;
		$qry = Database\Factory::getDBQuery(true);
		$qry->autocommit(false);
		$query = "SELECT posnr FROM kb3_navigation WHERE ID = $id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
		$row = $qry->getRow();
		$prev = $row['posnr']-1;

		$query = "UPDATE kb3_navigation SET posnr = (posnr+1) WHERE nav_type = 'top' AND posnr = $prev AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);

		$query = "UPDATE kb3_navigation SET posnr = (posnr-1) WHERE ID = $id AND KBSITE = '".KB_SITE."'";;
		$qry->execute($query);
		$qry->autocommit(true);
	}

	function renamePage($id, $name)
	{
		$id = (int) $id;
		$qry = Database\Factory::getDBQuery(true);
		$name = $qry->escape($name);
		$query = "UPDATE kb3_navigation SET descr ='$name' WHERE ID=$id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
	}

	function changeUrl($id, $url)
	{
		$qry = Database\Factory::getDBQuery(true);
		$id = (int)$id;
		$url = $qry->escape($url);
		$query = "UPDATE kb3_navigation SET url ='$url' WHERE ID=$id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
	}

	function newPage($descr, $url)
	{
		$qry = Database\Factory::getDBQuery(true);
		$descr = $qry->escape(preg_replace('/[^\w\d]/', '', $descr));
		$url = $qry->escape($url);
		$query = "SELECT max(posnr) as nr FROM kb3_navigation WHERE nav_type='top' AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
		$row = $qry->getRow();
		$posnr = $row['nr'] + 1;
		$query = "INSERT INTO kb3_navigation SET descr='$descr', intern=0, nav_type='top', url='$url', target ='', posnr=$posnr, page='ALL_PAGES', KBSITE = '".KB_SITE."'";
		$qry->execute($query);
	}

	function delPage($id)
	{
		$id = (int) $id;
		$qry = Database\Factory::getDBQuery(true);
		$query = "DELETE FROM kb3_navigation WHERE ID=$id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
	}

	function chgHideStatus($id, $status)
	{
		$id = (int) $id;
		$status = (int) $status % 2;
		$qry = Database\Factory::getDBQuery(true);
		$query = "UPDATE kb3_navigation SET hidden ='$status' WHERE ID=$id AND KBSITE = '".KB_SITE."'";
		$qry->execute($query);
	}
}