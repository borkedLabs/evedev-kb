<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\PageComponent\TopTable;

use EDK\Core\URI;
use EDK\Core\ImageURL;

class Pilot
{
	function __construct($toplist, $entity)
	{
		$this->toplist = $toplist;
		$this->entity_ = $entity;
	}

	function generate()
	{
		global $smarty;
		$this->toplist->generate();

		$i = 1;
		$rows = array();
		while ($row = $this->toplist->getRow())
		{
			$pilot = \EDK\Entity\Pilot::getByID($row['plt_id']);
			if($row['plt_externalid']) {
				$uri = \EDK\Core\EDK::urlFor('Pilot:external', ['id' => $row['plt_externalid']]);
				$img = ImageURL::getURL('Pilot', $row['plt_externalid'], 32);
			} else {
				$uri = \EDK\Core\EDK::urlFor('Pilot:detail', ['id' => $row['plt_externalid']]);

				$img = $pilot->getPortraitURL(32);
			}
			$rows[] = array(
				'rank' => $i,
				'name' => $pilot->getName(),
				'uri' => $uri,
				'portrait' => $img,
				'count' => $row['cnt']);
			$i++;
		}

		$smarty->assign('tl_name', 'Pilot');
		$smarty->assign('tl_type', $this->entity_);
		$smarty->assignByRef('tl_rows', $rows);

		return $smarty->fetch(get_tpl('toplisttable'));
	}
}
