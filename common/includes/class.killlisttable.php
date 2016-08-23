<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

/**
 * @package EDK
 */
class KillListTable
{
	/** @var KillList */
	private $kill_list_;
	/** @var integer */
	private $limit_;
	/** @var boolean */
	private $daybreak_;
	/** @var boolean */
	private $combined_;
    /** @var boolean */
    private $show_summary_;

	/**
	 * @param KillList $kill_list
	 */
	function __construct($kill_list)
	{
		$this->limit_ = 0;
		$this->kill_list_ = $kill_list;
		$this->daybreak_ = true;
	}

	function setDayBreak($daybreak)
	{
		$this->daybreak_ = $daybreak;
	}

	function setLimit($limit)
	{
		$this->limit_ = $limit;
	}

	function setCombined($combined = false)
	{
		$this->combined_ = $combined;
	}
        
        function setShowSummary($show_summary = false)
	{
		$this->show_summary_ = $show_summary;
	}

	function generate()
	{
		global $smarty;
		$prevdate = "";
		$this->kill_list_->rewind();
		$smarty->assign('daybreak', $this->daybreak_);
		$smarty->assign('comments_count', config::get('comments_count'));
        $smarty->assign('show_summary', $this->show_summary_);

		// evil hardcode-hack, don't do this at home kids ! ;)
		if (config::get('style_name') == 'revelations')
		{
			$smarty->assign('comment_white', '_white');
		}

		$c = 0;
		$kdpage = array('a', 'kill_detail', true);
		$krpage = array('a', 'kill_related', true);
		$kills = array();
        $killsInPeriod = 0;
        $lossesInPeriod = 0;
        $iskKilledInPeriod = 0.0;
        $iskLostInPeriod = 0.0;
                
		while ($kill  = $this->kill_list_->getKill())
		{
			if ($this->limit_ && $c >= $this->limit_)
			{
				break;
			}
			else
			{
				$c++;
			}

			$curdate = substr($kill->getTimeStamp(), 0, 10);
			if ($curdate != $prevdate)
			{
				if (count($kills) && $this->daybreak_)
				{
                    $structuredKillInfo = array('kills' => $kills, 'date' => strtotime($prevdate), 
                            'summary' => array('numberOfKills' => $killsInPeriod, 'numberOfLosses' => $lossesInPeriod));    
                    // only calculate efficiency if this is a combined list
                    if($this->combined_)
                    {
                        $efficiency = 1;
                        if($iskLostInPeriod + $iskKilledInPeriod > 0)
                        {
                            $efficiency = $iskKilledInPeriod / ($iskLostInPeriod + $iskKilledInPeriod);
                        }
                        $structuredKillInfo['summary']['efficiency'] = round($efficiency*100, 2);
                    }

                    $kl[] = $structuredKillInfo;
					$kills = array();
                    $killsInPeriod = 0;
                    $lossesInPeriod = 0;
                    $iskKilledInPeriod = 0.0;
                    $iskLostInPeriod = 0.0;
				}
				$prevdate = $curdate;
			}
			$kll = array();
			$kll['id'] = $kill->getID();
			$kll['victimshipimage'] = $kill->getVictimShipImage(32);
			$kll['victimshipname'] = $kill->getVictimShipName();
			$kll['victimshipclass'] = $kill->getVictimShipClassName();
			$kll['victim'] = $kill->getVictimName();
			$kll['victimiskloss'] = $kill->getISKLoss();
			if($kll['victimiskloss'] > 1000000000)
			{
				 $kll['victimiskloss'] = sprintf("%.02fb", $kll['victimiskloss']/1000000000);
			}
			elseif($kll['victimiskloss'] > 1000000)
			{
				 $kll['victimiskloss'] = sprintf("%.02fm", $kll['victimiskloss']/1000000);
			}
			elseif($kll['victimiskloss'] > 1000)
			{
				 $kll['victimiskloss'] = sprintf("%.02fk", $kll['victimiskloss']/1000);
			}
			$kll['victimcorp'] = $kill->getVictimCorpName();
			$kll['victimalliancename'] = $kill->getVictimAllianceName();
			$kll['fb'] = $kill->getFBPilotName();
			$kll['fbcorp'] = $kill->getFBCorpName();
			if ($kill->isClassified() && !Session::isAdmin()) {
				if (config::get('killlist_regionnames')) {
					$kll['region'] = Language::get("classified");
				}
				$kll['systemsecurity'] = "-";
				$kll['system'] = Language::get("classified");
			} else {
				if (config::get('killlist_regionnames')) {
					$kll['region'] = $kill->getSystem()->getRegionName();
				}
				$kll['systemsecurity'] = $kill->getSolarSystemSecurity();
				$kll['system'] = $kill->getSolarSystemName();
			}
			$kll['victimid'] = $kill->getVictimID();
			$kll['victimcorpid'] = $kill->getVictimCorpID();
			$kll['victimallianceid'] = $kill->getVictimAllianceID();
			$kll['victimshipid'] = $kill->getVictimShipExternalID();
			$kll['fbid'] = $kill->getFBPilotID();
			$kll['fbcorpid'] = $kill->getFBCorpID();
			$kll['inv'] = 0;
			if (config::get('killlist_involved')) {
				$kll['inv'] = $kill->getInvolvedPartyCount();
			}
			$kll['timestamp'] = $kill->getTimeStamp();
			if (config::get('killlist_alogo'))
			{
				// Need to return yet another value from killlists.
				$all = new Alliance($kill->getVictimAllianceID());
				if(strcasecmp($all->getName(), "None") != 0)
				{
					$kll['allianceexists'] = true;
					$kll['victimallianceicon'] = $all->getPortraitURL(32);
				}
				else
				{
					$kll['allianceexists'] = true;
					$crp = new Corporation($kill->getVictimCorpID());
					$kll['victimallianceicon'] = $crp->getPortraitURL(32);
				}
			}

			if (isset($kill->_tag))
			{
				$kll['tag'] = $kill->_tag;
			}

			$kll['fbplext'] = $kill->getFBPilotExternalID();
			$kll['plext'] = $kill->getFBPilotExternalID();
			if (config::get('comments_count'))
			{
				$kll['commentcount'] = $kill->countComment();
			}

			$kll['loss'] = false;
			$kll['kill'] = false;
            // determine kill or loss
            if(config::get('cfg_allianceid')
                            && in_array($kill->getVictimAllianceID(),
                                            config::get('cfg_allianceid'))) {
                    $kll['loss'] = true;
            } else if (config::get('cfg_corpid')
                            && in_array($kill->getVictimCorpID(),
                                            config::get('cfg_corpid'))) {
                    $kll['loss'] = true;
            } else if (config::get('cfg_pilotid')
                            && in_array($kill->getVictimID(),
                                            config::get('cfg_pilotid'))) {
                    $kll['loss'] = true;
            }
            $kll['kill'] = !$kll['loss'];

            // calculate summary
            if($kll['kill'])
            {
                $killsInPeriod++;
                $iskKilledInPeriod += $kill->getISKLoss();
            }

            else
            {
                $lossesInPeriod++;
                $iskLostInPeriod += $kill->getISKLoss();
            }
                        
			$kll['urldetail'] = edkURI::build($kdpage,
					array('kll_id', $kll['id'], true));
			if (!$kill->isClassified()) {
				$kll['urlrelated'] = edkURI::build($krpage,
					array('kll_id', $kll['id'], true));
			}
			$kll['victimextid'] = $kill->getVictimExternalID();
			$kll['urlvictim'] = edkURI::page('pilot_detail',
					$kll['victimextid'] ? $kll['victimextid'] : $kll['victimid'],
					$kll['victimextid'] ? 'plt_ext_id' : 'plt_id');
			$kll['urlfb'] = edkURI::page('pilot_detail',
					$kll['fbplext'] ? $kll['fbplext'] : $kll['fbid'],
					$kll['fbplext'] ? 'plt_ext_id' : 'plt_id');
			if ($kll['allianceexists'] ){
				$kll['urlvictimall'] = edkURI::page('alliance_detail',
					$kll['victimallianceid'], 'all_id');
			} 
			$kll['urlvictimcorp'] = edkURI::page('corp_detail',
					$kll['victimcorpid'], 'crp_id');
			$kll['urlfbcorp'] = edkURI::page('corp_detail',
					$kll['fbcorpid'], 'crp_id');
                        $kll['nearestCelestialName'] = $kill->getNearestCelestialName();
			event::call('killlist_table_kill', $kll);
			$kills[] = $kll;
		}
		event::call('killlist_table_kills', $kills);
		if (count($kills))
		{
			$structuredKillInfo = array('kills' => $kills, 'date' => strtotime($prevdate), 
                                'summary' => array('numberOfKills' => $killsInPeriod, 'numberOfLosses' => $lossesInPeriod));    
            // only calculate efficiency if this is a combined list
            if($this->combined_)
            {
                $efficiency = 1;
                if($iskLostInPeriod + $iskKilledInPeriod > 0)
                {
                    $efficiency = $iskKilledInPeriod / ($iskLostInPeriod + $iskKilledInPeriod);
                }
                $structuredKillInfo['summary']['efficiency'] = round($efficiency*100, 2);
            }

            $kl[] = $structuredKillInfo;
		}

		$smarty->assignByRef('killlist', $kl);
		$smarty->assign('killlist_iskloss', config::get('killlist_iskloss'));
		return $smarty->fetch(get_tpl('killlisttable'));
	}
}
