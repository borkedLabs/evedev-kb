<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Page\Component\TopTable;

use EDK\Core\URI;
use EDK\Core\Language;
use EDK\EVE\Item;

class Weapon
{
	function __construct(\EDK\Toplist\Base $toplist)
	{
		$this->toplist = $toplist;
	}

	function generate()
	{
		global $smarty;
		$this->toplist->generate();

		while ($row = $this->toplist->getRow())
		{
			$item = new Item($row['itm_id']);
			$rows[] = array(
				'rank' => false,
				'name' => $item->getName(),
				'uri' => \EDK\Core\EDK::urlFor('InvType:index', ['id' => $item->getID()]),
				'icon' => $item->getIcon(32),
				'count' => $row['cnt']);
		}

		$smarty->assign('tl_name', Language::get('weapon'));
		$smarty->assign('tl_type', Language::get('kills'));
		$smarty->assignByRef('tl_rows', $rows);

		return $smarty->fetch(get_tpl('toplisttable'));
	}
}
