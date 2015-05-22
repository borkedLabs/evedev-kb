<?php

namespace EDK\Command;

use EDK\Core\Config;

class ClearUp extends Command
{
	public function execute()
	{
		/** @var integer Maximum size for the store in megabytes. */
		$maxStoreSize = 512;
		/** @var integer Maximum image age in days. */
		$maxImageAge = 30;
		/** @var integer Maximum API cache age in days. */
		$maxAPIAge = 1;
		/** @var integer Maximum SQL query age in days. */
		$maxSQLAge = 2;
		/** @var integer Maximum cache age for everything else in days. */
		$maxOtherAge = 7;

		// disable query caching while the script is running.
		$qcache = Config::get('cfg_qcache');
		if($qcache)
		{
			println("File query cache disabled");
			Config::set('cfg_qcache', 0);
		}

		$pcache = Config::get('cache_enabled');
		if($pcache)
		{
			println("Page cache disabled");
			Config::set('cache_enabled', 0);
		}

		println("Removed ".CacheHandler::removeByAge('SQL/', $maxSQLAge * 24)." files from SQL");
		println("Removed ".CacheHandler::removeByAge('page/'.KB_SITE.'/', $maxOtherAge * 24)." files from page");
		println("Removed ".CacheHandler::removeByAge("templates_c/", $maxOtherAge * 24)." files from templates_c");
		println("Removed ".CacheHandler::removeByAge("mails/", $maxOtherAge * 24)." files from mail");
		// Let's let people see their latest beautiful creation in the character creator.
		println("Removed ".CacheHandler::removeByAge('img/', $maxImageAge * 24)." files from img");
		println("Removed ".CacheHandler::removeBySize('store/', $maxStoreSize)." files from store");
		println("Removed ".CacheHandler::removeByAge('api/', $maxAPIAge * 24)." files from api");

		if($qcache)
		{
			println("File query cache re-enabled");
			Config::set('cfg_qcache', 1);
		}

		if($pcache)
		{
			println("Page cache re-enabled");
			Config::set('cache_enabled', 1);
		}
	}
}