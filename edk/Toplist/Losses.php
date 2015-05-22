<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Toplist;

use EDK\Core\Config;

class Losses extends Base
{
	function generate()
	{
		$this->setSQLTop("SELECT COUNT(*) AS cnt, plt.plt_id, "
			."plt.plt_name, plt.plt_externalid FROM kb3_kills kll "
			."JOIN kb3_pilots plt on plt.plt_id = kll.kll_victim_id");
		$this->setSQLBottom("GROUP BY kll.kll_victim_id ORDER BY cnt DESC
                            LIMIT ".$this->limit);
		if (count($this->inc_vic_scl))
		{
			$this->setPodsNoobShips(true);
		}
		else
		{
			$this->setPodsNoobShips(config::get('podnoobs'));
		}
	}
}
