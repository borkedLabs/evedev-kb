<?php
/**
 * @package EDK
 */
use EDK\Core\Config;
use EDK\Core\Event;
use EDK\Core\URI;

global $xajax;
$xajax = new \xajax();
Event::register('page_assembleheader', 'edk_xajax::insertHTML');

// if mods depend on xajax they can register to xajax_initialised
// it gets called after all mods have been initialized
Event::register('mods_initialised', 'edk_xajax::lateProcess');

$uri = html_entity_decode(URI::build(URI::parseURI()));
if(strpos($uri, "?") === false) $uri .= "?xajax=1";
else $uri .= "&xajax=1";
$xajax->configure('requestURI', $uri);
$xajax->configure('deferScriptGeneration', false);
$xajax->configure('javascript URI', Config::get('cfg_kbhost')."/vendor/xajax/xajax/");

/**
 * @package EDK
 */
class edk_xajax
{
	public static function xajax()
	{
		global $xajax_enable;
		$xajax_enable = true;
	}

	// on page assembly check whether or not xajax is needed
	public static function insertHTML($obj)
	{
		global $xajax_enable, $xajax;
		if (!isset($xajax_enable)) {
			return;
		}
		
		$obj->addHeader($xajax->getJavascript());
	}

	public static function lateProcess()
	{
		// let all mods know we're here so they can register their functions
		event::call('xajax_initialised', $this);
		// Also register this for old mods registered to the ajax mod.
		event::call('mod_xajax_initialised', $this);

		// now process all xajax calls
		global $xajax;
		$xajax->processRequest();
	}
}

/**
 * Catch calls from old mods.
 * @package EDK
 */
class mod_xajax
{
	public static function xajax()
	{
		edk_xajax::xajax();
	}
}
