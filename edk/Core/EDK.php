<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\Core;
/*
 * @package EDK
 */
class EDK
{
	public static function slashfix($fix)
	{
		return addslashes(stripslashes($fix));
	}

	public static function roundsec($sec)
	{
		if ($sec <= 0) {
			$s = 0.0;
		} else {
			$s = $sec;
		}

		return number_format(round($s, 1), 1);
	}

	public static function loadMods()
	{
		$mods_active = explode(',', Config::get('mods_active'));
		$modOverrides = false;
		$modconflicts = array();

		$modInfo = array();
		foreach ($mods_active as $mod) {
			// load all active modules which need initialization
			if (file_exists(KB_ROOT.'mods/'.$mod.'/init.php'))
			{
				include(KB_ROOT.'mods/'.$mod.'/init.php');
			}
			
			if(!isset($modInfo[$mod])) {
					$modInfo[$mod] = array("name"=>$mod,
							"abstract"=>"Purpose unknown",
							"about"=>"");
			}
			
			if (file_exists(KB_ROOT.'mods/'.$mod.'/'.$page.'.php')) {
				$modconflicts[] = $mod;
				$modOverrides = true;
				$modOverride = $mod;
			}
		}
		
		if(count($modconflicts) > 1) {
			echo "<html><head></head><body>There are multiple active mods ".
							"for this page. Only one may be active at a time. All others ".
							"must be deactivated in the admin panel.<br>";
			foreach($modconflicts as $modname) {
					echo $modname." <br> ";
			}
			echo "</body>";
			die();
		}

		$none = '';
		Event::call('mods_initialised', $none);
		
		return $modOverrides;
	}
}
