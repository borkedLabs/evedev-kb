<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Toplist;

class TopList_PodKiller extends TopList_Kills
{
	function __construct()
	{
		trigger_error("Using ".get_class($this)." is deprecated. Use TopList_Kills and set ship classes as needed.", E_USER_NOTICE);
		$this->TopList_Kills();
		$this->addVictimShipClass(2); // capsule
	}
}
