<?php

class FeedCommand extends CronCommand
{
	public function execute()
	{
		println("Starting IDFeed Import");
		$cronStartTime = microtime(true);

		$config = new Config(KB_SITE);

		$feeds = config::get("fetch_idfeeds");
		if( !is_array($feeds) )
		{
			println("No feeds configured");
			exit(1);
		}

		foreach($feeds as $feedId => &$feedConfig) 
		{
			println("Fetching IDFeed: ".$feedId);
			$this->getIDFeed($feedId, $feedConfig);
		}
		
		println("Time taken = ".(microtime(true) - $cronStartTime)." seconds");
	}
	
	protected function getIDFeed(&$key, &$val)
	{
		// Just in case, check for empty urls.
		if(empty($val['url']))
		{
			return '';
		}
		
		$feedfetch = new IDFeed();
		$feedfetch->setID();
		$feedfetch->setAllKills(1);
		
		if(!$val['lastkill'])
		{
			$feedfetch->setStartDate(time() - 60*60*24*7);
		}
		else
		{
			$feedfetch->setStartKill($val['lastkill'] + 1, true);
		}

		if($feedfetch->read($val['url']) !== false)
		{
			if(intval($feedfetch->getLastInternalReturned()) > $val['lastkill'])
			{
				$val['lastkill'] = intval($feedfetch->getLastInternalReturned());
			}
			
			println("Feed: ".$val['url']);
			println(count($feedfetch->getPosted())." kills were posted and ".
							count($feedfetch->getSkipped())." were skipped"
                         . " (".$feedfetch->getNumberOfKillsFetched()." kills fetched)");
			println("Last kill ID returned was ".$val['lastkill']);
			
			if ($feedfetch->getParseMessages())
			{
				println(implode(PHP_EOL, $feedfetch->getParseMessages()));
			}
		}
		else
		{
			$msg  = '';
			$msg .= "Error reading feed: ".$val['url'];
			if(!$val['lastkill']) 
			{
				$msg .= ", Start time = ".(time() - 60*60*24*7);
			}
			$msg .= $feedfetch->errormsg();
			
			println($msg);
		}
	}
}
