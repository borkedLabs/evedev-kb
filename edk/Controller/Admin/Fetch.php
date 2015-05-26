<?php
/*
 * @package EDK
 */

namespace EDK\Controller\Admin;

use EDK\Core\Config;
use EDK\Core\URI;
use EDK\CREST\ValueFetcher;
use EDK\Killmail\Importer;
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
	
	function getZKBApi(&$fetchConfig)
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
}