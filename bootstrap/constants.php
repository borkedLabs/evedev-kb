<?php

/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
if (!defined('LATEST_DB_UPDATE')) {
	define('LATEST_DB_UPDATE', "037");
}

define('KB_CACHEDIR', __DIR__.'/../cache');
define('KB_CACHEURL', '/cache');
define('KB_PAGECACHEDIR', KB_CACHEDIR.'/page');
define('KB_MAILCACHEDIR', KB_CACHEDIR.'/mails');
define('KB_QUERYCACHEDIR', KB_CACHEDIR.'/SQL');
define('KB_UPDATE_URL', 'http://evekb.org/downloads');
define('API_SERVER', "https://api.eveonline.com");
define('IMG_SERVER', "https://image.eveonline.com");

// current version: major.minor.sub.ccpDBupdateNo
// even numbers for minor = development version
define('KB_VERSION', '4.2.10.0');
define('KB_RELEASE', '(Mosaic 1.0)');
define('KB_CCP_DB_VERSION', '112318');
define('KB_CCP_DB_DATE', 'May 3, 2015');
define('ID_FEED_VERSION', 1.30);
define('ZKB_FETCH_VERSION', 1.0);
define('KB_APIKEY_LEGACY', 1);
define('KB_APIKEY_CORP', 2);
define('KB_APIKEY_CHAR', 4);
define('KB_APIKEY_BADAUTH', 8);
define('KB_APIKEY_EXPIRED', 16);
