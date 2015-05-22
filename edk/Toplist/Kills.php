<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Toplist;

use EDK\Core\Config;

class Kills extends Base
{
	function generate()
	{
		$sql = "select count(ind.ind_kll_id) as cnt, ind.ind_plt_id as plt_id, plt.plt_name
                from kb3_kills kll
	      INNER JOIN kb3_inv_detail ind
		      on ( ind.ind_kll_id = kll.kll_id )
              INNER JOIN kb3_pilots plt
	 	      on ( plt.plt_id = ind.ind_plt_id )";

		$this->setSQLTop($sql);

		$this->setSQLBottom(" group by ind.ind_plt_id order by 1 desc limit ".$this->limit);
		if (count($this->inc_vic_scl))
		{
			$this->setPodsNoobShips(true);
		}
		else
		{
			$this->setPodsNoobShips(Config::get('podnoobs'));
		}
	}
}
