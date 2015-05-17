<?php

class ZkbCommand extends CronCommand
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		$fetchConfigs = ZKBFetch::getAll();
		$html = '';

		foreach($fetchConfigs AS &$fetchConfig)
		{
			$html .= $this->getZKBApi($fetchConfig);
		}

		echo $html."<br />\n";

		println("Time taken = ".(microtime(true) - $cronStartTime)." seconds.");
	}

	private function getZKBApi(&$fetchConfig)
	{
		$html = '';
		// Just in case, check for empty urls.
		if(is_null($fetchConfig->getUrl())) 
		{
			return 'No URL given<br />';
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
			$html .= "ZKBApi: ".$fetchConfig->getUrl()."<br />\n";
			$html .= count($fetchConfig->getPosted())." kills were posted and ".
						count($fetchConfig->getSkipped())." were skipped. ";
						
			$html .= "Timestamp of last kill: ".strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp());
			$html .= "<br />\n";
			
			if ($fetchConfig->getParseMessages()) 
			{
				$html .= implode("<br />", $fetchConfig->getParseMessages())."<br/>";
			}
		}
		catch (Exception $ex) 
		{
			$html .= "Error reading feed: ".$fetchConfig->getUrl()."<br/>";
			$lastKillTimestampFormatted = strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp());
			$html .= $ex->getMessage();
			$html .= ", Start time = ".$lastKillTimestampFormatted;
			$html .= "<br/><br/>";
		}
		
		return $html;
	}
}