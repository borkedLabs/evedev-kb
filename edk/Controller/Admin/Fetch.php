<?php
/*
 * @package EDK
 */

namespace EDK\Controller\Admin;

use EDK\Core\Config;
use EDK\Core\URI;
use EDK\CREST\ValueFetcher;
use EDK\Entity\Pilot;
use EDK\Entity\Corporation;
use EDK\Entity\Alliance;
use EDK\Killmail\Importer;
use EDK\Killmail\Importer\IDFeed;
use EDK\Page\Page;


class Fetch extends Base
{
	/** @var Page The Page object used to display this page. */
	public $page;

	function valuesAction()
	{
		global $smarty, $menubox;
		
		$page = new Page('Fetcher - Item Values from CREST');
		$page->setAdmin();

		if($_POST['submit'])
		{
			// Set timeout and memory, we neeeeed it ;)
			@set_time_limit(0);
			@ini_set('memory_limit',999999999);
			error_reporting(0);

			/**
			* 	Author: Niels Brink (HyperBeanie)
			*
			*	Licence: Do what you like with it, credit me as the original author
			*		 Not warrantied for anything, might eat your cat.  Your responsibility.
			*/

			// Check if user wants to use a local file
			$url = $_POST['turl'];
			// If not set, use default
			if (!$url) 
			{
				$url = ValueFetcher::$CREST_URL;
			}

			Config::set('fetchurl', $url);

			$ValueFetcherCrest = new ValueFetcher($url);

			$html = "<center>";
			try
			{
				$count = $ValueFetcherCrest->fetchValues();
				$html .= "Fetched and updated <b>". $count."</b> items!<br /><br />";

			}
			catch (Exception $e)
			{
				$html .= "Error in fetch: " . $e->getMessage();
				$html .= "<br />This was probably caused by an incorrect filename";
			}
			$html .= "</center>";
		}
		else
		{
			// Get from config
			$url = Config::get('itemPriceCrestUrl');
			$timestamp = Config::get('lastfetch');
			$time = date('r', $timestamp);
			if ($url == null)
			{
				$url = ValueFetcher::$CREST_URL;
			}

			$html .= 'Last update: '.$time.'<br /><br />';

			$html .= '<form method="post" action="'.URI::page("admin_value_fetch").'">';
			$html .= '<table width="100%" border="1">';
			$html .= '<tr><td>Filename</td><td colspan="2"><input type="text" name="turl" id="turl" value="'.$url.'" size="110" /></td></tr>';
			$html .= '<tr><td colspan="3" align="center"><i>Leave above field empty to reset to default.</i></td></tr>';
			if ((time() - $timestamp) < 86400)
			{
					$html .= '<tr><td colspan="3" align="center"><b>YOU HAVE UPDATED LESS THAN 24 HOURS AGO!</b></td></tr>';
			}
			$html .= '<tr><td colspan="3"><button value="submit" type="submit" name="submit">Fetch</button></td></tr>';
			$html .= '</table></form>';
			$html .= '<br /><a href="'.URI::page('admin_value_editor').'">Manually update values</a>';
		}

		$page->setContent($html);
		$page->addContext($menubox->generate());
		$page->generate();
	}
	
	function zkbAction()
	{
		global $smarty, $menubox;
		
		$page = new Page("Administration - zKillboard Fetch v" . ZKB_FETCH_VERSION);
		$page->setCachable(false);
		$page->setAdmin();
		$html = "";

		// add new fetch config
		if($_POST['add'])
		{
			$newFetchUrl = $_POST['newFetchUrl'];
			if(strlen(trim($newFetchUrl)) == 0)
			{
				$html .=  "Error: Can't add zKB Fetch with empty URL!<br/>";
			}
			
			else
			{
				// must end with a slash
				if(substr($newFetchUrl, -1) != '/')
				{
					$newFetchUrl .= '/';
				}
				$newFetchTimestamp = trim($_POST['newFetchTimestamp']);
				$newFetchTimestamp = strtotime($newFetchTimestamp);

				$NewZKBFetch = new Importer\ZKB();
				$NewZKBFetch->setUrl($newFetchUrl);
				$NewZKBFetch->setLastKillTimestamp($newFetchTimestamp);

				try
				{
					$NewZKBFetch->add();
				} 

				catch (Exception $ex) 
				{
					$html .= $ex->getMessage();
				}
			}
		}

		$fetchConfigs = Importer\ZKB::getAll();


		// saving urls and options
		if ($_POST['submit'] || $_POST['fetch'])
		{
			if(is_null($fetchConfigs)) 
					{
				$fetchConfigs = array();
			}
			foreach($fetchConfigs AS &$fetchConfig) 
			{
				$id = $fetchConfig->getID();
				if ($_POST[$id]) 
				{
					$lastKillTimestampFormatted = strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp());
					if($_POST['lastKillTimestamp'.$id] != $lastKillTimestampFormatted) 
					{
						$lastKillTimestampNew = strtotime($_POST['lastKillTimestamp'.$id]);
						if($lastKillTimestampNew !== FALSE)
						{
							$fetchConfig->setLastKillTimestamp($lastKillTimestampNew);
						}
					}
					
					// reset the feed lastkill details if the URL or api status has changed
					if($_POST[$id] != $fetchConfig->getUrl()) 
					{
						$fetchConfig->setUrl($_POST[$id]);
					}
					
					if ($_POST['delete'] && in_array ($id, $_POST['delete'])) 
					{
						Importer\ZKB::delete($id);
					}
				} 
				
				else 
				{
					Importer\ZKB::delete($id);
			}
			}

			if($_POST['post_no_npc_only_zkb'])
			{
				Config::set('post_no_npc_only_zkb', 1);
			}

			else
			{
				Config::set('post_no_npc_only_zkb', 0);
			}
			
			// set the negative timestamp offset
			if(isset($_POST['killTimestampOffset']) && is_numeric($_POST['killTimestampOffset']))
			{
				Config::set('killTimestampOffset', (int) $_POST['killTimestampOffset']);
			}
			
		}

		// update fetch configs again, since we could have deleted some above
		$fetchConfigs = Importer\ZKB::getAll();

		// building the request query and fetching of the feeds
		if ($_POST['fetch'])
		{
			foreach($fetchConfigs AS &$fetchConfig)
			{
					if(!($_POST['fetchApi'] && in_array ($fetchConfig->getID(), $_POST['fetchApi'])) || is_null($fetchConfig->getUrl())) 
					{
						continue;
					}
					$html .= $this->getZKBApi($fetchConfig);
			}
		}

		// generating the html
		$rows = array();
		foreach($fetchConfigs as &$fetchConfig) 
		{
			$key = $fetchConfig->getID();
			if (!isset($_POST['fetchApi'][$key]) || $_POST['fetchApi'][$key]) 
				{
					$fetch=false;
			} else {
					$fetch = true;
			}
				$lastKillTimestampFormatted = strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp());
			$rows[] = array('id'=>$key, 'uri'=>$fetchConfig->getUrl(), 'lastKillTimestmap'=>$lastKillTimestampFormatted,  'fetch'=>!$fetch);
		}

		$smarty->assignByRef('rows', $rows);
		$smarty->assign('results', $html);
		$smarty->assign('post_no_npc_only_zkb', Config::get('post_no_npc_only_zkb'));
		$killTimestampOffset = Config::get('killTimestampOffset');
		if(is_null($killTimestampOffset) || !is_numeric($killTimestampOffset))
		{
			$killTimestampOffset = Importer\ZKB::$KILL_TIMESTAMP_OFFSET_DEFAULT;
		}
		$smarty->assign('killTimestampOffset', $killTimestampOffset);
		$smarty->assign("currentTimeUtc", gmdate("Y-m-d H:i:s", time()));
		$page->addContext($menubox->generate());
		$page->setContent($smarty->fetch(get_tpl('admin_zkbfetch')));
		$page->generate();
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
				$fetchConfig->setKillTimestampOffset(Config::get('killTimestampOffset'));
				$fetchConfig->setIgnoreNpcOnlyKills((boolean)(Config::get('post_no_npc_only_zkb')));
				$fetchConfig->processApi();
				$html .= "ZKBApi: ".$fetchConfig->getUrl()."<br />\n";
				$html .= count($fetchConfig->getPosted())." kills were posted and ".
							count($fetchConfig->getSkipped())." were skipped. ";
				$html .= "Timestamp of last kill: ".strftime('%Y-%m-%d %H:%M:%S', $fetchConfig->getLastKillTimestamp());
				$html .= "<br />\n";
				if ($fetchConfig->getParseMessages()) 
				{
					$html .= implode("<br />", $fetchConfig->getParseMessages())."<br />";
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
	
	
	function idfeedAction()
	{
		global $smarty, $menubox;
		
		$page = new Page("Administration - IDFeed Syndication " . ID_FEED_VERSION);
		$page->setCachable(false);
		$page->setAdmin();

		$feeds = Config::get("fetch_idfeeds");
		// Add an empty feed to the list, or create with one empty feed.
		if(is_null($feeds)) {
			$feeds[] = array('url'=>"", 'apikills'=>0, 'lastkill'=>0);
			Config::set("fetch_idfeeds", $feeds);
		} else {
			$feeds[] = array('url'=>"", 'apikills'=>0, 'lastkill'=>0);
		}

		$feedcount = count($feeds);

		// saving urls and options
		if ($_POST['submit'] || $_POST['fetch'])
		{
			if(is_null($feeds)) {
				$feeds = array();
			}
			foreach($feeds as $key => &$val) {
				// Use the md5 of the url as a key for each feed.
				$url = md5($val['url']);

				if ($_POST[$url]) {
					$val['apikills'] = 0;
					if($_POST['lastkill'.$url] != $val['lastkill']) {
						$val['lastkill'] = intval($_POST['lastkill'.$url]);
					}
					// reset the feed lastkill details if the URL or api status has changed
					if($_POST[$url] != $val['url'] ) {
						$val['url'] = $_POST[$url];
						$val['lastkill'] = 0;
					}
					if ($_POST['delete'] && in_array ($url, $_POST['delete'])) {
						unset($feeds[$key]);
					}
				} else {
					unset($feeds[$key]);
				}
			}
			$newlist = array();
			foreach($feeds as $key => &$val) {
				if ($val['url']) {
					$newlist[$val['url']] = $val;
				}
			}
			$feeds = &$newlist;
			Config::set("fetch_idfeeds", $feeds);
				
				if($_POST['post_no_npc_only_feed'])
				{
					Config::set('post_no_npc_only_feed', 1);
				}
				
				else
				{
					Config::set('post_no_npc_only_feed', 0);
				}
			$feeds[] = array('url'=>"", 'apikills'=>0, 'lastkill'=>0);
		}

		// building the request query and fetching of the feeds
		if ($_POST['fetch'])
		{
			foreach($feeds as $key => &$val)
			{
				if(!($_POST['fetch_feed'] && in_array (md5($val['url']), $_POST['fetch_feed']))
					|| empty($val['url'])) continue;

				if (isIDFeed($val['url'])) {
					$html .= getIDFeed($key, $val);
				} else {
					$html .= getOldFeed($key, $val);
				}
				Config::set("fetch_idfeeds", $feeds);
			}
		}
		// generating the html
		$rows = array();
		foreach($feeds as $key => &$val) {
			$key = md5($val['url']);
			if (!isset($_POST['fetch_feed'][$key])
					|| $_POST['fetch_feed'][$key]) {
				$fetch=false;
			} else {
				$fetch = true;
			}
			$rows[] = array('name'=>$key, 'uri'=>$val['url'], 'lastkill'=>$val['lastkill'], 'fetch'=>!$fetch);
		}
		$smarty->assignByRef('rows', $rows);
		$smarty->assign('post_no_npc_only_feed', Config::get('post_no_npc_only_feed'));
		$smarty->assign('results', $html);
		$page->addContext($menubox->generate());
		$page->setContent($smarty->fetch(get_tpl('admin_idfeed')));
		$page->generate();

	}
	
	/**
	 * Fetch the board owners.
	 * @return array Array of id strings to add to URLS
	 */
	function getOwners()
	{
		$myids = array();
		if(!defined('MASTER') || !MASTER) {
			foreach(Config::get('cfg_pilotid') as $entity) {
				$pilot = new Pilot($entity);
				$myids[] = '&pilot=' . urlencode($pilot->getName());
			}

			foreach(Config::get('cfg_corpid') as $entity) {
				$corp = new Corporation($entity);
				$myids[] = '&corp=' . urlencode($corp->getName());
			}
			foreach(Config::get('cfg_allianceid') as $entity) {
				$alli = new Alliance($entity);
				$myids[] = '&alli=' . urlencode($alli->getName());
			}
		}
		return $myids;
	}

	private function getIDFeed(&$key, &$val)
	{
		$html = '';
		// Just in case, check for empty urls.
		if(empty($val['url'])) {
			return 'No URL given<br />';
		}
		$feedfetch = new IDFeed();
		$feedfetch->setID();
		$feedfetch->setAllKills(1);
		if(!$val['lastkill']) {
			$feedfetch->setStartDate(time() - 60*60*24*7);
		} else if($val['apikills']) {
			$feedfetch->setStartKill($val['lastkill'] + 1);
		} else {
			$feedfetch->setStartKill($val['lastkill'] + 1, true);
		}

		if($feedfetch->read($val['url']) !== false) {
			if($val['apikills']
					&& intval($feedfetch->getLastReturned()) > $val['lastkill']) {
				$val['lastkill'] = intval($feedfetch->getLastReturned());
			} else if(!$val['apikills']
					&& intval($feedfetch->getLastInternalReturned())
							> $val['lastkill']) {
				$val['lastkill'] = intval($feedfetch->getLastInternalReturned());
			}
			$html .= "IDFeed: ".$val['url']."<br />\n";
			$html .= count($feedfetch->getPosted())." kills were posted and ".
							count($feedfetch->getSkipped())." were skipped.<br />\n";
			if ($feedfetch->getParseMessages()) {
				$html .= implode("<br />", $feedfetch->getParseMessages());
			}
		} else {
			$html .= "Error reading feed: ".$val['url'];
			if (!$val['lastkill']) {
				$html .= ", Start time = ".(time() - 60 * 60 * 24 * 7);
			} else if ($val['apikills']) {
				$html .= ", Start kill = ".($val['lastkill']);
			}
			$html .= $feedfetch->errormsg();
		}
		return $html;
	}

	/**
	 * Check if this is an IDFeed.
	 * The url parameter is modified if needed to refer directly to the IDFeed.
	 * @param string $url
	 * @return string HTML describing the fetch result.
	 */
	private function isIDFeed(&$url)
	{
		// If the url has idfeed or p=ed_feed in it then assume the URL is correct
		// and return immediately.
		if (strpos($url, 'idfeed')) {
			// Believe the user ...
			return true;
		} else if (strpos($url, 'p=ed_feed')) {
			// Griefwatch feed.
			return false;
		}

		// With no extension standard EDK will divert the idfeed fetcher to the idfeed
		if(strpos($url, '?') === false) {
			$urltest = $url.'?kll_id=-1';
			if ($this->checkIDFeed($urltest)) {
				return true;
			}
		}

		// Either the bare url didn't work or we don't have a bare url.
		// Either add 'a=idfeed' to the url or change 'a=feed'.
		// If we find an idfeed then make the url change permanent and return true
		// Otherwise we have an old feed, return false.
		if(strpos($url, '?')) {
			$urltest = preg_replace('/\?.*/', '?a=idfeed&kll_id=-1', $url);
		} else if (substr($url, -1) == '/') {
			$urltest = $url."?a=idfeed&kll_id=-1";
		} else {
			$urltest = $url."/?a=idfeed&kll_id=-1";
		}
		if ($this->checkIDFeed($urltest)) {
			if(strpos($url, '?a=feed')) {
				$url = preg_replace('/\?a=feed/', '?a=idfeed', $url);
			} else if(strpos($url, '?')) {
				$url = preg_replace('/\?/', '?a=idfeed&', $url);
			} else if (substr($url, -1) == '/') {
				$url = $url."?a=idfeed";
			} else {
				$url = $url."/?a=idfeed";
			}
			return true;
		} else {
			return false;
		}
	}

	private function getOldFeed(&$key, &$val)
	{
		$html = 'RSS Feed: ';
		// Just in case, check for empty urls.
		if(empty($val['url'])) return 'No URL given<br />';

		$url = $val['url'];
		if (!strpos($url, 'a=feed')) {
			if (strpos($url, '?')) {
				$url = str_replace('?', '?a=feed&', $url);
			} else {
				$url .= "?a=feed";
			}
		}
		$feedfetch = new Fetcher();

		$myids = $this->getOwners();
		$lastkill = 0;
		foreach($myids as $myid) {
			// If a last kill id is specified fetch all kills since then
			if($val['lastkill'] > 0) {
				$urltmp = $url.'&combined=1&lastkllid='.$val['lastkill'];
				//TODO: Put some methods into the fetcher to get this more neatly.
				$html .= preg_replace('/(<div class=\'block-header2\'>|<\/div>)/',
					'', $feedfetch->grab($urltmp, $myid, $val['trust']))."\n";
				if(intval($feedfetch->lastkllid_) < $lastkill || !$lastkill)
						$lastkill = intval($feedfetch->lastkllid_);
				// Check if feed used combined list. get losses if not
				if(!$feedfetch->combined_) {
					$html .= preg_replace('/(<div class=\'block-header2\'>|<\/div>)/',
						'', $feedfetch->grab($urltmp, $myid."&losses=1", $val['trust']))."\n";
					if(intval($feedfetch->lastkllid_) < $lastkill || !$lastkill)
							$lastkill = intval($feedfetch->lastkllid_);
				}
				// Store most recent kill id fetched
				if($lastkill > $val['lastkill']) {
					$val['lastkill'] = $lastkill;
				}
			} else {
				// If no last kill is specified then fetch by week
				// Fetch for current and previous weeks, both kills and losses
				for($l = $week - 1; $l <= $week; $l++)
				{
					$html .= preg_replace('/(<div class=\'block-header2\'>|<\/div>)/',
						'', $feedfetch->grab($url . "&year=" . $year . "&week=" . $l,
							$myid, $val['trust'])) . "\n";
					if(intval($feedfetch->lastkllid_) < $lastkill
							|| !$lastkill) {
						$lastkill = intval($feedfetch->lastkllid_);
					}
					$html .= preg_replace('/(<div class=\'block-header2\'>|<\/div>)/',
						'', $feedfetch->grab($url . "&year=" . $year . "&week=" . $l,
							$myid . "&losses=1", $val['trust'])) . "\n";
					if(intval($feedfetch->lastkllid_) < $lastkill
							|| !$lastkill) {
						$lastkill = intval($feedfetch->lastkllid_);
					}
				}
				// Store most recent kill id fetched
				if($lastkill > $val['lastkill']) {
					$val['lastkill'] = $lastkill;
				}
			}
		}
		return $html;
	}

	/**
	 * @param string $url 
	 * @return boolean True if this is an IDFeed, false if not.
	 */
	function checkIDFeed( $url) {
		$http = new http_request($url);
		$http->set_useragent("EDK IDFeedfetcher Check");
		$http->set_timeout(0.5);
		$res = $http->get_content();
		if ($http->status['timed_out']) {
			return false;
		} else if ($res && strpos($res, 'edkapi')) {
			return true;
		}
		return false;
	}
}