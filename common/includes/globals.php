<?php

/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

use EDK\Core\Config;
use EDK\Core\Event;

function slashfix($fix)
{
	return addslashes(stripslashes($fix));
}

function roundsec($sec)
{
	if ($sec <= 0) {
		$s = 0.0;
	} else {
		$s = $sec;
	}

	return number_format(round($s, 1), 1);
}

/**
 * Check if a version of this template exists in this theme or for the igb.
 * If client is igb check if theme has an igb version. If not check in default
 *  theme for one. If client is not igb check if the theme has the template.
 *  If not then again return the default template.
 *
 *  @param string $name containing the name of the template.
 */
function get_tpl($name)
{
	global $themename;
	event::call('get_tpl', $name);

	// If a specific tempate file is already asked for then simply return it.
	if (substr($name, -3) == 'tpl') {
		return $name;
	}

	if ($themename == 'default') {
		if (IS_IGB && file_exists('./themes/default/templates/igb_'.$name
						.'.tpl')) {
			return 'igb_'.$name.'.tpl';
		}
		return $name.'.tpl';
	} else {
		if (IS_IGB) {
			if (is_file('./themes/'.$themename.'/templates/igb_'.$name.'.tpl')) {
				return 'igb_'.$name.'.tpl';
			} else if (is_file('./themes/default/templates/igb_'.$name.'.tpl')) {
				return '../../default/templates/igb_'.$name.'.tpl';
			}
		}
		if (is_file('./themes/'.$themename.'/templates/'.$name.'.tpl')) {
			 return $name.'.tpl';
		} else if (is_file('./themes/default/templates/'.$name.'.tpl')) {
			return '../../default/templates/'.$name.'.tpl';
		}
	}
	return $name.'.tpl';
}

/**
 * this is currently only a wrapper but might get
 * timestamp adjustment options in the future
 *
 * @param string $format
 * @param string $timestamp
 * @return string
 */
function kbdate($format, $timestamp = null)
{
	if ($timestamp === null) {
		return gmdate($format);
	} else {
		return gmdate($format, $timestamp);
	}
}

/**
 *
 * @return string
 */
function getYear()
{
	if (Config::get('show_monthly')) {
		return gmdate('Y');
	}

	$test = kbdate('o');
	if ($test == 'o') {
		$day = gmdate('j');
		$week = gmdate('W');
		if ($week == 1 && $day > 14) {
			return gmdate('Y') - 1;
		} else if ($week > 50 && $day < 8) {
			return gmdate('Y') + 1;
		}
		return gmdate('Y');
	}
	return $test;
}

/**
 * Return the number of weeks in the given year.
 * @param integer $year the year to count weeks for. Default is the current year.
 * @return integer the number of weeks in the given year.
 */
function getWeeks($year = null)
{
	if (is_null($year)) {
		$year = getYear();
	}
	$weeks = date('W', mktime(1, 0, 0, 12, 31, $year));
	return $weeks == 1 ? 52 : $weeks;
}

/**
 * Return start date for the given week, month, year or date.
 *
 * weekno > monthno > startWeek > yearno
 * weekno > monthno > yearno
 * startDate and endDate are used if they restrict the date range further
 * monthno, weekno and startweek are not used if no year is set
 *
 * @param integer $week
 * @param integer $year
 * @param integer $month
 * @param integer $startweek
 * @param string $startdate String representation of a date readable by
 * strtotime ('UTC' is added to all dates passed in)
 * @return integer
 */
function makeStartDate($week = 0, $year = 0, $month = 0, $startweek = 0,
		$startdate = 0)
{
	$qstartdate = 0;
	if (intval($year) > 2000) {
		if ($week) {
			if ($week < 10) {
				$week = '0'.$week;
			}
			$qstartdate = strtotime($year.'W'.$week.' UTC');
		} else if ($month) {
			$qstartdate = strtotime($year.'-'.$month.'-1 00:00 UTC');
		} else if ($startweek) {
			$qstartdate = strtotime($year.'W'.$startweek.' UTC');
		} else {
			$qstartdate = strtotime($year.'-1-1 00:00 UTC');
		}
	}
	//If set use the latest startdate and earliest enddate set.
	if ($startdate && $qstartdate < strtotime($startdate." UTC")) {
		$qstartdate = strtotime($startdate." UTC");
	}
	return $qstartdate;
}

/**
 * Return end date for the given week, month, year or date.
 *
 * Priority order of date filters:
 * weekno > monthno > startWeek > yearno
 * weekno > monthno > yearno
 * startDate and endDate are used if they restrict the date range further
 * monthno, weekno and startweek are not used if no year is set
 *
 * @param integer $week
 * @param integer $year
 * @param integer $month
 * @param string $enddate String representation of a date readable by strtotime
 * ('UTC' is added to all dates passed in)
 * @return integer unix timestamp for the calculated date or 0 if no input.
 */
function makeEndDate($week = 0, $year = 0, $month = 0, $enddate = '')
{
	$qenddate = 0;
	if ($year) {
		if ($week) {
			if ($week < 10) {
				$week = '0'.$week;
			}
			$qenddate = strtotime($year.'W'.$week.' +7days -1second UTC');
		} else if ($month) {
			if ($month == 12) {
				$qenddate = strtotime($year.'-12-31 23:59 UTC');
			} else {
				$qenddate = strtotime($year.'-'.($month + 1).'-1 00:00 - 1 minute UTC');
			}
		} else {
			$qenddate = strtotime($year.'-12-31 23:59 UTC');
		}
	}
	//If set use the earliest enddate.
	if ($enddate && (!$qenddate || ($qenddate && $qenddate > strtotime($enddate." UTC")))) {
		$qenddate = strtotime($enddate." UTC");
	}

	return $qenddate;
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
function isNewerVersion($newVersion, $baseVersion)
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


function loadMods()
{
    $mods_active = explode(',', Config::get('mods_active'));
    $modOverrides = false;
    $modconflicts = array();

    $modInfo = array();
    foreach ($mods_active as $mod) {
            // load all active modules which need initialization
            if (file_exists('mods/'.$mod.'/init.php')) {
                    include('mods/'.$mod.'/init.php');
            }
            if(!isset($modInfo[$mod])) {
                    $modInfo[$mod] = array("name"=>$mod,
                            "abstract"=>"Purpose unknown",
                            "about"=>"");
            }
            if (file_exists('mods/'.$mod.'/'.$page.'.php')) {
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
    event::call('mods_initialised', $none);
	
    return $modInfo;
}