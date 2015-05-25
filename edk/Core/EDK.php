<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\Core;

use EDK\Entity\Alliance;
use EDK\Entity\Corporation;
use EDK\Entity\Pilot;
use \Smarty;
/*
 * @package EDK
 */
class EDK
{
	private static $Slim = null;
	
	public static function init(\Slim\Slim $app)
	{
		self::$Slim = $app;
		
		// determine the request scheme
		$requestScheme = "http";
		if (isset($_SERVER['HTTPS'])) 
		{
			// Set to a non-empty value if the script was queried through the HTTPS protocol. 
			// ISAPI with IIS sets the value to "off", if the request was not madet throught the HTTPS protocol
			if (!empty($_SERVER['HTTPS']) && 'off' != strtolower($_SERVER['HTTPS']) && '' != trim($_SERVER['HTTPS']))
			{
				$requestScheme = "https";
			}
		} 

		// fallback: check the server port
		elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) 
		{
			$requestScheme = "https";
		}
		
		$requestScheme .= "://";

		// If there is no config then redirect to the install folder.
		if(!defined('KB_SITE'))
		{
			$html = "<html><head><title>Board not configured</title></head>";
			$html .= "<body>Killboard configuration not found. Go to ";
			$url = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
			$url = substr($url, 0, strrpos($url, '/',1)).'/install/';
			$url = preg_replace('/\/{2,}/','/',$url);
			$url = $requestScheme.$url;
			$html .= "<a href='".$url."'>install</a> to install a new killboard";
			$html .= "</body></html>";
			die($html);
		}
		// Check the install folder is not accessible
		else if(file_exists("install") && !file_exists("install/install.lock"))
		{
			$html = "<html><head><title>Installation in progress</title></head>";
			$html .= "<body><p>Installation folder must be removed or locked to proceed.</p>";
			$url = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
			$url = substr($url, 0, strrpos($url, '/',1)).'/install/';
			$url = preg_replace('/\/{2,}/','/',$url);
			$html .= "<p>Go to <a href='".$requestScheme.$url."'>Install</a> to install a new killboard.</p>";
			$html .= "</body></html>";
			die($html);
		}
		
		// Set the default encoding to UTF-8
		header('Content-Type: text/html; charset=UTF-8');

		
		// load the config from the database
		$config = new Config();
		if(!config::get('cfg_kbhost'))
		{
			config::put('cfg_kbhost',
					$requestScheme.$_SERVER['HTTP_HOST'].
					substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'],"/")));
		}
		if(!config::get('cfg_img'))
		{
			config::put('cfg_img',
					config::get('cfg_kbhost')."/img");
		}
		
		define('KB_HOST', config::get('cfg_kbhost'));
		define('IMG_URL', config::get('cfg_img'));
		
		if(substr(IMG_URL, -4) == '/img') define('IMG_HOST', substr(IMG_URL, 0, strpos(IMG_URL, "/img")));
		else  define('IMG_HOST', KB_HOST);

		$page = URI::getArg('a', 0);
		URI::usePath(config::get('cfg_pathinfo'));
		if (defined('KB_PHP')) URI::setRoot(KB_PHP);

		if(isset($_GET['xajax'])) require_once('common/includes/xajax.functions.php');

		// Serve feeds to feed fetchers.
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'EDK Feedfetcher') !== false) {
			$page = 'feed';
		} else if(strpos($_SERVER['HTTP_USER_AGENT'], 'EDK IDFeedfetcher') !== false) {
		// Serve idfeeds to idfeed fetchers.
			$page = 'idfeed';
		} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'EVE-IGB') !== false) {
		// check for the igb
			define('IS_IGB', true);
		} else {
			define('IS_IGB', false);
		}
		
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'EVE-IGB') !== false) {
		// check for the igb
			define('IS_IGB', true);
		} else {
			define('IS_IGB', false);
		}
		
		
		// set up themes.
		if(isset($_GET['theme'])) {
			$themename = preg_replace('/[^0-9a-zA-Z-_]/','',$_GET['theme']);
		} else {
			$themename = config::get('theme_name');
		}

		if(isset($_GET['style'])) {
			$stylename = preg_replace('/[^0-9a-zA-Z-_]/','',$_GET['style']);
		} else {
			$stylename = config::get('style_name');
		}

		if(!is_dir("themes/".$themename."/templates")) {
			$themename = 'default';
		}

		if(!file_exists("themes/".$themename."/".$stylename.".css")) {
			$stylename = 'default';
		}

		define('THEME_URL', config::get('cfg_kbhost').'/themes/'.$themename);

		// set up titles/roles
		role::init();

		// start Session management
		Session::init();
		
		
		// Check if the KB internal database structure needs updating
		// or if we need to install a new CCP DB
		if((config::get('DBUpdate') < LATEST_DB_UPDATE) || (config::get('CCPDbVersion') < KB_CCP_DB_VERSION))
		{
			// Check db is installed.
			if(config::get('cfg_kbhost'))
			{
				$url = preg_replace('/^http:\/\//','',KB_HOST."/update/");
				$url = preg_replace('/\/{2,}/','/',$url);
				header('Location: '.$requestScheme.$url);
				die;
			}
			// Should not be able to reach this point but have this just in case
			else
			{
				$html = "<html><head><title>Board not configured</title></head>";
				$html .= "<body>Killboard configuration not found. Go to ";
				$url = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
				$url = substr($url, 0, strrpos($url, '/',1)).'/install/';
				$url = preg_replace('/\/+/','/',$url);
				$url = $requestScheme.$url;
				$html .= "<a href='".$url."'>install</a> to install a new killboard";
				$html .= "</body></html>";
				die($html);
			}
		}

		// all admin files are now in the admin directory and preload the menu
		if (substr($page, 0, 5) == 'admin') {
			require_once('common/admin/admin_menu.php');
			$page = 'admin/'.$page;
		} else if(config::get('cfg_locked') && $page != 'login' && !Session::isAdmin()) {
			$page = "locked";
		}

		$settingsPage = (substr($page, 0, 9) == 'settings_');

		if(file_exists("themes/".$themename."/init.php")) {
			include_once("themes/".$themename."/init.php");
		}
		
		global $smarty;
		/**
		 * Smarty templating.
		 * 
		 * @global Smarty $smarty
		 */
		$smarty = new Smarty();
		if(!Session::isAdmin()) {
			// Disable checking of timestamps for templates to improve performance.
			$smarty->compile_check = false;
		}
		$smarty->template_dir = "./themes/$themename/templates";

		if(!is_dir(KB_CACHEDIR.'/templates_c/'.$themename)) {
			mkdir(KB_CACHEDIR.'/templates_c/'.$themename);
		}
		$smarty->compile_dir = KB_CACHEDIR.'/templates_c/'.$themename;

		$smarty->cache_dir = KB_CACHEDIR.'/data';
		$smarty->assign('theme_url', THEME_URL);
		if ($stylename != 'default' || $themename != 'default') {
			$smarty->assign('style', $stylename);
		}
		$smarty->assign('img_url', IMG_URL);
		$smarty->assign('img_host', IMG_HOST);
		$smarty->assign('kb_host', KB_HOST);
		$smarty->assignByRef('config', $config);
		$smarty->assign('is_IGB', IS_IGB);

		// Set the name of the board owner.
		$owners = array();
		if(config::get('cfg_allianceid'))
		{
			foreach(config::get('cfg_allianceid') as $owner)
			{
				$alliance=new Alliance($owner);
				$owners[] = htmlentities($alliance->getName());
			}
			unset($alliance);
		}
		if (config::get('cfg_corpid'))
		{
			foreach(config::get('cfg_corpid') as $owner)
			{
				$corp = new Corporation($owner);
				$owners[] = htmlentities($corp->getName());
			}
			unset($corp);
		}
		if (config::get('cfg_pilotid'))
		{
			foreach(config::get('cfg_corpid') as $owner)
			{
				$pilot = new Pilot($owner);
				$owners[] = htmlentities($pilot->getName());
			}
			unset($pilot);
		}
		if(!$owners) $smarty->assign('kb_owner', false);
		else $smarty->assign('kb_owner', implode(',', $owners));

		// Show a system message on all pages if the init stage has generated any.
		if(isset($boardMessage)) $smarty->assign('message', $boardMessage);
	}
	
	public static function urlFor($route, $args)
	{
		return self::$slim->urlFor($route, $args);
	}
	
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
