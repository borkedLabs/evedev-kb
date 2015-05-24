<?php

namespace EDK\Command;

use EDK\Core\Config;

class Feed extends Command
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		$feeds = Config::get("fetch_idfeeds");
		if( !is_array($feeds) )
		{
			println("No feeds configured");
			exit(1);
		}
		
		foreach($feeds as $key => &$val)
		{
			if ($this->isIDFeed($val['url']))
			{
				println("Fetching IDFeed: ".$key);
				$this->getIDFeed($key, $val);
			}
			else
			{
				println("Fetching RSS Feed: ".$key);
				$this->getOldFeed($key, $val);
			}
		}
	}
	
	protected function getIDFeed(&$key, &$val)
	{
		// Just in case, check for empty urls.
		if(empty($val['url']))
		{
			return '';
		}
		
		$feedfetch = new \IDFeed();
		$feedfetch->setID();
		
		if($val['apikills'])
		{
			$feedfetch->setAllKills(0);
		} 
		else 
		{
			$feedfetch->setAllKills(1);
		}
		
		if(!$val['lastkill'])
		{
			$feedfetch->setStartDate(time() - 60*60*24*7);
		} 
		else if($val['apikills'])
		{
			$feedfetch->setStartKill($val['lastkill'] + 1);
		} 
		else
		{
			$feedfetch->setStartKill($val['lastkill'] + 1, true);
		}

		if($feedfetch->read($val['url']) !== false)
		{
			if($val['apikills'] 
					&& intval($feedfetch->getLastReturned()) > $val['lastkill'])
			{
				$val['lastkill'] = intval($feedfetch->getLastReturned());
			} 
			else if(!$val['apikills']
					&& intval($feedfetch->getLastInternalReturned())
							> $val['lastkill'])
			{
				$val['lastkill'] = intval($feedfetch->getLastInternalReturned());
			}
			
			println("Feed: ".$val['url']);
			println(count($feedfetch->getPosted())." kills were posted and ".
				count($feedfetch->getSkipped())." were skipped.");
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
			else if($val['apikills'])
			{
				 $msg .= ", Start kill = ".($val['lastkill']);
			}
			$msg .= $feedfetch->errormsg();
			
			println($msg);
		}
	}

	/**
	 * Fetch the board owners.
	 * @return array Array of id strings to add to URLS
	 */
	private function getOwners()
	{
		$myids = array();
		if(!defined('MASTER') || !MASTER)
		{
			foreach(Config::get('cfg_pilotid') as $entity)
			{
				$pilot = new \Pilot($entity);
				$myids[] = '&pilot=' . urlencode($pilot->getName());
			}

			foreach(Config::get('cfg_corpid') as $entity)
			{
				$corp = new \Corporation($entity);
				$myids[] = '&corp=' . urlencode($corp->getName());
			}
			
			foreach(Config::get('cfg_allianceid') as $entity)
			{
				$alli = new \Alliance($entity);
				$myids[] = '&alli=' . urlencode($alli->getName());
			}
		}
		return $myids;
	}

	private function getOldFeed(&$key, &$val)
	{
		// Just in case, check for empty urls.
		if(empty($val['url']))
		{
			return '';
		}

		$url = $val['url'];
		if (!strpos($url, 'a=feed'))
		{
			if (strpos($url, '?'))
			{
				$url = str_replace('?', '?a=feed&', $url);
			}
			else
			{
				$url .= "?a=feed";
			}
		}
		$feedfetch = new \Fetcher();

		$myids = $this->getOwners();
		$lastkill = 0;
		foreach($myids as $myid)
		{
			// If a last kill id is specified fetch all kills since then
			if($val['lastkill'] > 0)
			{
				$urltmp = $url.'&combined=1&lastkllid='.$val['lastkill'];
				println(preg_replace('/<div.+No kills added from feed.+<\/div>/',
					'', $feedfetch->grab($urltmp, $myid, $val['trust'])));
					
				if(intval($feedfetch->lastkllid_) < $lastkill || !$lastkill)
				{
					$lastkill = intval($feedfetch->lastkllid_);
				}
					
				// Check if feed used combined list. get losses if not
				if(!$feedfetch->combined_)
				{
					println(preg_replace('/<div.+No kills added from feed.+<\/div>/',
						'', $feedfetch->grab($urltmp, $myid."&losses=1", $val['trust'])));
						
					if(intval($feedfetch->lastkllid_) < $lastkill || !$lastkill)
					{
						$lastkill = intval($feedfetch->lastkllid_);
					}
				}
				
				// Store most recent kill id fetched
				if($lastkill > $val['lastkill'])
				{
					$val['lastkill'] = $lastkill;
				}
			}
			else
			{
				// If no last kill is specified then fetch by week
				// Fetch for current and previous weeks, both kills and losses
				for($l = $week - 1; $l <= $week; $l++)
				{
					println(preg_replace('/<div.+No kills added from feed.+<\/div>/',
						'', $feedfetch->grab($url . "&year=" . $year . "&week=" . $l,
							$myid, $val['trust'])));
							
					if(intval($feedfetch->lastkllid_) < $lastkill
							|| !$lastkill) {
						$lastkill = intval($feedfetch->lastkllid_);
					}
					
					println(preg_replace('/<div.+No kills added from feed.+<\/div>/',
						'', $feedfetch->grab($url . "&year=" . $year . "&week=" . $l,
							$myid . "&losses=1", $val['trust'])));
							
					if(intval($feedfetch->lastkllid_) < $lastkill
							|| !$lastkill)
					{
						$lastkill = intval($feedfetch->lastkllid_);
					}
				}
				// Store most recent kill id fetched
				if($lastkill > $val['lastkill'])
				{
					$val['lastkill'] = $lastkill;
				}
			}
		}
	}

	/**
	 * Check if this is an IDFeed.
	 * The url parameter is modified if needed to refer directly to the IDFeed.
	 * @param string $url
	 */
	private function isIDFeed(&$url)
	{
		if (!$url)
		{
			// No point checking further.
			return false;
		}
		else if (strpos($url, 'idfeed'))
		{
			// Believe the user ...
			return true;
		}

		if(strpos($url, '?'))
		{
			$urltest = preg_replace('/\?.*/', '?a=idfeed&kll_id=-1', $url);
		} 
		else if (substr($url, -1) == '/')
		{
			$urltest = $url."?a=idfeed&kll_id=-1";
		}
		else
		{
			$urltest = $url."/?a=idfeed&kll_id=-1";
		}
		
		
		$client = new GuzzleHttp\Client();
		$response = $client->get($urltest , [
												'headers' => [
													'User-Agent' => 'EDK IDFeedfetcher Check',
												],
												'timeout' => 10
												] );
		
		$res = $response->getBody();
		
		if ($res && strpos($res, 'edkapi'))
		{
			if(strpos($url, '?a=feed'))
			{
				$url = preg_replace('/\?a=feed/', '?a=idfeed', $url);
			}
			else if(strpos($url, '?'))
			{
				$url = preg_replace('/\?/', '?a=idfeed&', $url);
			}
			else if (substr($url, -1) == '/')
			{
				$url = $url."?a=idfeed";
			}
			else
			{
				$url = $url."/?a=idfeed";
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}
}