<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\Page\Component;

use EDK\Core\Config;
use EDK\Core\Event;
use EDK\Core\Session;
use EDK\Core\URI;
use EDK\Entity\Alliance;
use EDK\Entity\Corporation;

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

	function generate()
	{
		global $smarty;
		$prevdate = "";
		$this->kill_list_->rewind();
		$smarty->assign('daybreak', $this->daybreak_);
		$smarty->assign('comments_count', Config::get('comments_count'));

		// evil hardcode-hack, don't do this at home kids ! ;)
		if (Config::get('style_name') == 'revelations')
		{
			$smarty->assign('comment_white', '_white');
		}

		$c = 0;
		$kills = array();
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
					$kl[] = array('kills' => $kills, 'date' => strtotime($prevdate));
					$kills = array();
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
				if (Config::get('killlist_regionnames')) {
					$kll['region'] = Language::get("classified");
				}
				$kll['systemsecurity'] = "-";
				$kll['system'] = Language::get("classified");
			} else {
				if (Config::get('killlist_regionnames')) {
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
			if (Config::get('killlist_involved')) {
				$kll['inv'] = $kill->getInvolvedPartyCount();
			}
			$kll['timestamp'] = $kill->getTimeStamp();
			if (Config::get('killlist_alogo'))
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
			if (Config::get('comments_count'))
			{
				$kll['commentcount'] = $kill->countComment();
			}

			$kll['loss'] = false;
			$kll['kill'] = false;
			if ($this->combined_)
			{
				if(Config::get('cfg_allianceid')
						&& in_array($kill->getVictimAllianceID(),
								Config::get('cfg_allianceid'))) {
					$kll['loss'] = true;
				} else if (Config::get('cfg_corpid')
						&& in_array($kill->getVictimCorpID(),
								Config::get('cfg_corpid'))) {
					$kll['loss'] = true;
				} else if (Config::get('cfg_pilotid')
						&& in_array($kill->getVictimID(),
								Config::get('cfg_pilotid'))) {
					$kll['loss'] = true;
				}
				$kll['kill'] = !$kll['loss'];
			}
			$kll['urldetail'] = \EDK\Core\EDK::urlFor('KillDetail:index', ['id' => $kll['id']]);

			if (!$kill->isClassified())
			{
				$kll['urlrelated'] = \EDK\Core\EDK::urlFor('KillRelated:index', ['id' => $kll['id']]);
			}
			
			$kll['victimextid'] = $kill->getVictimExternalID();
					
			if( $kll['victimextid'] )
			{
				$kll['urlvictim'] = \EDK\Core\EDK::urlFor('Pilot:external', ['id' => $kll['victimextid']]);
			}
			else
			{
				$kll['urlvictim'] = \EDK\Core\EDK::urlFor('Pilot:detail', ['id' => $kll['victimid']]);
			}
			
			if( $kll['fbplext'] )
			{
				$kll['urlfb'] = \EDK\Core\EDK::urlFor('Pilot:external', ['id' => $kll['fbplext']]);
			}
			else
			{
				$kll['urlfb'] = \EDK\Core\EDK::urlFor('Pilot:detail', ['id' => $kll['fbid']]);
			}
					
			if ($kll['allianceexists'] )
			{
				$kll['urlvictimall'] = \EDK\Core\EDK::urlFor('Alliance:detail', ['id' => $kll['victimallianceid']]);
			} 
			$kll['urlvictimcorp'] = \EDK\Core\EDK::urlFor('Corp:detail', ['id' => $kll['victimcorpid']]);
			$kll['urlfbcorp'] = \EDK\Core\EDK::urlFor('Corp:detail', ['id' => $kll['fbcorpid']]);
			event::call('killlist_table_kill', $kll);
			$kills[] = $kll;
		}
		event::call('killlist_table_kills', $kills);
		if (count($kills))
		{
			$kl[] = array('kills' => $kills, 'date' => strtotime($prevdate));
		}

		$smarty->assignByRef('killlist', $kl);
		$smarty->assign('killlist_iskloss', Config::get('killlist_iskloss'));
		return $smarty->fetch(get_tpl('killlisttable'));
	}
}
