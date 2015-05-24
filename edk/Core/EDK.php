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

	public static function loadMods($page)
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
	
	/**
	 * compares $newVersion ot $baseVersion and returns TRUE if $newVersion
	 * is a newer version than $baseVersion; the format for $newVersion and $baseVersion
	 * must be:
	 * <mainVersion>.<minorVersion>.<codeRelease>.<dbRelease>
	 * examples:
	 * 4.2.9.0
	 * 4.2.10.0
	 * @param string $newVersion the new version to compare against the $baseVersion
	 * @param string $baseVersion the base version to compare against
	 */
	public static function isNewerVersion($newVersion, $baseVersion)
	{
		// split at the dots
		$newVersionParsed = explode(".", $newVersion);
		$baseVersionParsed = explode(".", $baseVersion);
		
		// check for array sizes
		$numberOfVersionParts = max(count($newVersionParsed), count($baseVersionParsed));
		
		// make arrays equally sized, fill up with zeroes
		// because version 4.2 is equal to 4.2.0.0
		while(count($newVersionParsed) < $numberOfVersionParts)
		{
			array_push($newVersionParsed, 0);
		}
		
		while(count($baseVersionParsed) < $numberOfVersionParts)
		{
			array_push($baseVersionParsed, 0);
		}
		
		// now compare each array index against each other
		for($versionPart = 0; $versionPart < $numberOfVersionParts; $versionPart++)
		{
			$newVersionPart = (int) $newVersionParsed[$versionPart];
			$baseVersionPart = (int) $baseVersionParsed[$versionPart];
			
			// check each version part; if the corresponding part of $newVersion is bigger, then it's a new version
			if($newVersionPart > $baseVersionPart)
			{
				return TRUE;
			}
			
			// version is older
			if($newVersionPart < $baseVersionPart)
			{
					return FALSE;
			}

			// at this point the two version parts are identical, keep comparing
		}
		
		// at this point none parts in $newVersion is bigger than the corresponding part in $baseVersion
		return FALSE;
	}
}
