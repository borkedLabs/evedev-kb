<?php

class CacheCommand extends CronCommand
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		println("Starting Alliance list update");

		// Alliance
		$allianceApi = new API_Alliance();
		
		println("Caching Alliance XML");
		$allianceApi->fetchalliances();
		if(!is_null($allianceApi->getError()))
		{
			println("Error occurred while fetching Alliance list:");
			println($allianceApi->getMessage());
		}
		else
		{
			println("Finished successfully");
		}
	}
}