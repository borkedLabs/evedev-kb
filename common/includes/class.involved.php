<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

use EDK\Core\Config;

/**
 * @package EDK
 */
class involved
{
	function involved()
	{
		trigger_error('The class "involved" may only be invoked statically.', E_USER_ERROR);
	}

	public static function load(&$killlist, $type = 'kill')
	{
		if($type == 'kill')
		{
			if(Config::get('cfg_pilotid'))
					$killlist->addInvolvedPilot(Config::get('cfg_pilotid'));
			if(Config::get('cfg_corpid'))
					$killlist->addInvolvedCorp(Config::get('cfg_corpid'));
			if(Config::get('cfg_allianceid'))
					$killlist->addInvolvedAlliance(Config::get('cfg_allianceid'));
		}
		elseif($type == 'loss')
		{
			if(Config::get('cfg_pilotid'))
					$killlist->addVictimPilot(Config::get('cfg_pilotid'));
			if(Config::get('cfg_corpid'))
					$killlist->addVictimCorp(Config::get('cfg_corpid'));
			if(Config::get('cfg_allianceid'))
					$killlist->addVictimAlliance(Config::get('cfg_allianceid'));
		}
		elseif($type == 'combined')
		{
			if(Config::get('cfg_pilotid'))
					$killlist->addCombinedPilot(Config::get('cfg_pilotid'));
			if(Config::get('cfg_corpid'))
					$killlist->addCombinedCorp(Config::get('cfg_corpid'));
			if(Config::get('cfg_allianceid'))
					$killlist->addCombinedAlliance(Config::get('cfg_allianceid'));
		}
	}

	public static function add(&$arr, &$ids)
	{
		if(is_numeric($ids)) $arr[] = $ids;
		else if(is_array($ids))
		{
			if(is_numeric(reset($ids))) $arr = array_merge($arr, $ids);
			else
			{
				if(method_exists(reset($ids), 'getID'))
				{
					foreach($ids as $obj)
					{
						$arr[] = $obj->getID();
					}
				}
				else trigger_error("IDs passed were not of a valid type.", E_USER_WARNING);
			}
		}
		else $arr[] = $ids->getID();
	}
}
