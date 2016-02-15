<?php

class ZkbCommand extends CronCommand
{
	public function execute()
	{
		$cronStartTime = microtime(true);
		println("Starting zKB Import");

		$config = new Config(KB_SITE);

		$fetchConfigs = ZKBFetch::getAll();

		foreach($fetchConfigs AS &$fetchConfig)
		{
			$this->getZKBApi($fetchConfig);
		}
		
		println('Time taken = '.(microtime(true) - $cronStartTime).' seconds.');
	}

	private function getZKBApi(&$fetchConfig)
	{
		// Just in case, check for empty urls.
		if(is_null($fetchConfig->getUrl())) 
		{
			println("No URL given");
			exit(1);
		}
		
		if(!$fetchConfig->getLastKillTimestamp())
		{
			$fetchConfig->setLastKillTimestamp(time() - 60 * 60 * 24 * 7);
		}
			
		try
		{
			$fetchConfig->setKillTimestampOffset(config::get('killTimestampOffset'));
			$fetchConfig->setIgnoreNpcOnlyKills((boolean)(config::get('post_no_npc_only_zkb')));
			$fetchConfig->processApi();
			println("ZKBApi: ".$fetchConfig->getUrl());
			println(count($fetchConfig->getPosted())." kills were posted and ".
						count($fetchConfig->getSkipped())." were skipped. ");
						
            println(count($fetchConfig->getPosted())." kills were posted and ".count($fetchConfig->getSkipped())." were skipped "
                                                . "(".$fetchConfig->getNumberOfKillsFetched()." kills fetched)");
			
			println("Timestamp of last kill: ".strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp()));
			
			if ($fetchConfig->getParseMessages()) 
			{
				println(implode(PHP_EOL, $fetchConfig->getParseMessages()));
			}
		}
		catch (Exception $ex) 
		{
			println("Error reading feed: ".$fetchConfig->getUrl());
			println($ex->getMessage());
			$lastKillTimestampFormatted = strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp());
			println("ZKB API Kill Start time = ".$lastKillTimestampFormatted);
		}
		
		return $html;
	}
}
