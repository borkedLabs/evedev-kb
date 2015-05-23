<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\Page;

use EDK\Core\Event;
use EDK\Core\URI;
use \DBFactory;

/*
 * @package EDK
 */
class InvGroup extends \pageAssembly
{
	/** @var Page */
	public $page = null;
	/** @var integer */
	public $groupID = 0;

	function __construct()
	{
		parent::__construct();

		$this->queue("start");
		$this->queue("details");
	}

	public function generate()
	{
		Event::call("invtype_assembling", $this);
		$html = $this->assemble();
		$this->page->setContent($html);

		$this->page->generate();
	}
	
	function start()
	{
		$this->groupID = (int)URI::getArg('id', 1);
		$this->page = new Page('Item Database');
	}

	function details()
	{
		global $smarty;
		if (!$this->groupID)
		{
			$this->page->setTitle('Error');
			return 'This ID is not a valid group ID.';
		}
		$sql = 'SELECT * FROM kb3_item_types d'.
				' WHERE d.itt_id = '.$this->groupID;
		$qry = DBFactory::getDBQuery();;
		$qry->execute($sql);
		$row = $qry->getRow();

		$this->page->setTitle('Item Database - '.$row['itt_name'].' Index');

		$sql = 'SELECT * FROM kb3_invtypes d'.
				' WHERE d.groupID = '.$this->groupID.
				' ORDER BY d.typeName ASC';
		$qry = DBFactory::getDBQuery();;
		$qry->execute($sql);
		$rows= array();
		while($row = $qry->getRow()) {
			$rows[] = array('typeID'=>$row['typeID'], 'typeName'=>$row['typeName']);
		}
		$smarty->assign('rows', $rows);
		return $smarty->fetch(get_tpl('groupdb'));
	}
}