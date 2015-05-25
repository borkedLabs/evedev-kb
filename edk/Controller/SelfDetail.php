<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

namespace EDK\Controller;

use EDK\Cache\Cache;
use EDK\Cache\Cacheable;
use EDK\Core\Config;
use EDK\Core\Event;
use EDK\Core\URI;
use EDK\Entity\Pilot;
use EDK\Entity\Corporation;
use EDK\Entity\Alliance;
use EDK\Page\Page;

/*
 * @package EDK
 */
class SelfDetail extends Base
{
    /**
     * Construct the Alliance Details object.
     * Set up the basic variables of the class and add the functions to the
     *  build queue.
     */
    function __construct()
    {
        $this->queue("start");
        $this->queue("display");
		$this->generate();
		
		Cache::generate();
    }
	

	public function generate()
	{
		if(count(Config::get('cfg_allianceid'))
			+ count(Config::get('cfg_corpid'))
			+ count(Config::get('cfg_pilotid')) > 1)
		{

			Event::call("self_assembling", $this);
			$html = $this->assemble();
			$this->page->setContent($html);

			$this->page->generate();
		}
		else if(Config::get('cfg_allianceid'))
		{
			$alls = Config::get('cfg_allianceid');
			/* @var $alliance Alliance */
			$alliance = Cacheable::factory('\EDK\Entity\Alliance', $alls[0]);
			if ($alliance->getExternalID()) {
				$url = URI::page('alliance_detail', $alliance->getExternalID(), 'all_ext_id');
			} else {
				$url = URI::page('alliance_detail', $alls[0], 'all_id');
			}
			header("Location: ".htmlspecialchars_decode($url));
			die;
		}
		elseif(Config::get('cfg_corpid'))
		{
			$corps = Config::get('cfg_corpid');
			/* @var $corp Corporation */
			$corp = Cacheable::factory('\EDK\Entity\Corporation', $corps[0]);
			if ($corp->getExternalID()) {
				$url = URI::page('corp_detail', $corp->getExternalID(), 'crp_ext_id');
			} else {
				$url = URI::page('corp_detail', $corps[0], 'crp_id');
			}
			header("Location: ".htmlspecialchars_decode($url));
			die;
		}
		elseif(Config::get('cfg_pilotid'))
		{
			$pilots = Config::get('cfg_pilotid');
			/* @var $pilot Pilot */
			$pilot = Cacheable::factory('\EDK\Entity\Pilot', $pilots[0]);
			if ($pilot->getExternalID()) {
				$url = URI::page('pilot_detail', $pilot->getExternalID(),
						'plt_ext_id');
			} else {
				$url = URI::page('pilot_detail', $pilots[0],
						'plt_id');
			}
			header("Location: ".htmlspecialchars_decode($url));
			die;
		}
		else
		{
			header("Location: ".htmlspecialchars_decode(edkURI::page('about')));
			die;
		}
	}
	
    function start()
    {
        $this->page = new Page('Board Owners');
    }

	function summaryTable()
	{
		$summarytable = new \KillSummaryTable();
		involved::load($summarytable,'kill');
		return $summarytable->generate();
	}

    function display()
    {
        global $smarty;
		$alls = $corps = $pilots = false;
		if(Config::get('cfg_allianceid'))
		{
			$alls = array();
			foreach(Config::get('cfg_allianceid') as $entity)
			{
				$alliance = new Alliance($entity);
				if ($alliance->getExternalID()) {
					$url = URI::page('alliance_detail', $alliance->getExternalID(),
							'all_ext_id');
				} else {
					$url = URI::page('alliance_detail', $alliance->getID(),
							'all_id');
				}
				$alls[] = array('id' => $alliance->getID(),
					'extid' => $alliance->getExternalID(),
					'name' => $alliance->getName(),
					'portrait' => $alliance->getPortraitURL(128),
					'url' => $url);
			}
		}
		
		if(Config::get('cfg_corpid'))
		{
			$corps = array();
			foreach(Config::get('cfg_corpid') as $entity)
			{
				$corp = new Corporation($entity);
				if ($corp->getExternalID()) {
					$url = URI::page('corp_detail', $corp->getExternalID(),
							'crp_ext_id');
				} else {
					$url = URI::page('corp_detail', $corp->getID(),
							'crp_id');
				}
				$corps[] = array('id' => $corp->getID(),
					'extid' => $corp->getExternalID(),
					'name' => $corp->getName(),
					'portrait' => $corp->getPortraitURL(128),
					'url' => $url);
			}
		}
		
		if(Config::get('cfg_pilotid'))
		{
			$pilots = array();
			foreach(Config::get('cfg_pilotid') as $entity)
			{
				$pilot = new Pilot($entity);
				if ($pilot->getExternalID()) {
					$url = URI::page('pilot_detail', $pilot->getExternalID(),
							'plt_ext_id');
				} else {
					$url = URI::page('pilot_detail', $pilot->getID(),
							'plt_id');
				}
				$pilots[] = array('id' => $pilot->getID(),
					'extid' => $pilot->getExternalID(),
					'name' => $pilot->getName(),
					'portrait' => $pilot->getPortraitURL(128),
					'url' => $url);
			}
		}

		$smarty->assignByRef('alliances', $alls);
		$smarty->assignByRef('corps', $corps);
		$smarty->assignByRef('pilots', $pilots);

        return $smarty->fetch(get_tpl('self'));
    }
}

