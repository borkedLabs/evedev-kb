<?php

class CacheCommand extends CronCommand
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		println("Running Cron_Cache on " . gmdate("M d Y H:i"));
		println("");

		// Alliance
		$myAlliAPI = new API_Alliance();
		
		println("Caching Alliance XML");
		$Allitemp .= $myAlliAPI->fetchalliances();
	}
}