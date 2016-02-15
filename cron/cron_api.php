<?php

class ApiCommand extends CronCommand
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		define('KB_TITLE', config::get('cfg_kbtitle'));

		if (!$dir = config::get('cache_dir'))
		{
			$dir = 'cache/data';
		}
		if(!defined('KB_CACHEDIR'))
		{
			define('KB_CACHEDIR', $dir);
		}
		
		$cronStartTime = microtime(true);
		println("Starting API Import");

		$myEveAPI = new API_KillLog();
		$myEveAPI->iscronjob_ = true;

		$qry = new DBQuery();
		$qry->execute("SELECT * FROM kb3_api_keys WHERE key_kbsite = '" . KB_SITE . "' ORDER BY key_name");
		
		while ($row = $qry->getRow())
		{
			if(isset($_GET['feed']) && $_GET['feed'] && $row['key_id'] != $_GET['feed'])
			{
				continue;
			}

			println("Importing Mails for " . $row['key_name']);
			println($myEveAPI->Import($row['key_name'], $row['key_id'], $row['key_key'], $row['key_flags']));
		}

		println("Time taken = ".(microtime(true) - $cronStartTime)." seconds.");
	}
}