<?php

/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
if (!defined('LATEST_DB_UPDATE')) {
    define('LATEST_DB_UPDATE', "039");
}

define('KB_CACHEDIR', __DIR__.'/../../cache');
define('KB_CACHEURL', '/cache');
define('KB_PAGECACHEDIR', KB_CACHEDIR.'/page');
define('KB_MAILCACHEDIR', KB_CACHEDIR.'/mails');
define('KB_QUERYCACHEDIR', KB_CACHEDIR.'/SQL');
define('KB_UPDATE_URL', 'http://evekb.org/downloads');
define('API_SERVER', "https://api.eveonline.com");
define('IMG_SERVER', "https://imageserver.eveonline.com");
define('CREST_PUBLIC_URL', 'https://public-crest.eveonline.com');

// current version: major.minor.sub.ccpDBupdateNo
// even numbers for minor = development version
define('KB_VERSION', '4.2.17.0');
define('KB_RELEASE', '(Frostline 1.0)');
define('KB_CCP_DB_VERSION', '116241');
define('KB_CCP_DB_DATE', 'Dec 9, 2015');
define('ID_FEED_VERSION', 1.40);
define('ZKB_FETCH_VERSION', 1.0);
define('KB_APIKEY_LEGACY', 1);
define('KB_APIKEY_CORP', 2);
define('KB_APIKEY_CHAR', 4);
define('KB_APIKEY_BADAUTH', 8);
define('KB_APIKEY_EXPIRED', 16);
