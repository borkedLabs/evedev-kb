<?php
require_once('common/includes/class.corp.php');
require_once('common/includes/class.alliance.php');
require_once('common/includes/class.killlist.php');
require_once('common/includes/class.killlisttable.php');
require_once('common/includes/class.killsummarytable.php');
require_once('common/includes/class.toplist.php');
require_once('common/includes/class.pageAssembly.php');

class pAllianceDetail extends pageAssembly
{
	//! Construct the Alliance Details object.

	/** Set up the basic variables of the class and add the functions to the
	 *  build queue.
	 */
	function __construct()
	{
		parent::__construct();
		$this->scl_id = intval($_GET['scl_id']);
		$this->all_id = intval($_GET['all_id']);
		$this->all_external_id = intval($_GET['all_external_id']);
		$this->view = $_GET['view'];
		$this->crp_id = intval($_GET['crp_id']);

		$this->menuOptions = array();

		$this->queue("start");
		$this->queue("stats");
		$this->queue("summaryTable");
		$this->queue("killList");

	}
	//! Start constructing the page.

	/*! Prepare all the shared variables such as dates and check alliance ID.
	 *
	 */
	function start()
	{
		if (!$this->all_id && !$this->all_external_id)
		{
			if (ALLIANCE_ID)
			{
				$this->all_id = ALLIANCE_ID;
			}
			else
			{
				echo 'no valid alliance id specified<br/>';
				exit;
			}
		}

		if(!$this->all_id && $this->all_external_id)
		{
			$qry = new DBQuery();
			$qry->execute("SELECT all_id FROM kb3_alliances WHERE all_external_id = ".$this->all_external_id);
			if($qry->recordCount())
			{
				$row = $qry->getRow();
				$this->all_id = $row['all_id'];
			}
			else
			{
				echo 'no valid alliance id specified<br/>';
				exit;
			}
		}

		$this->month = $_GET['m'];
		$this->year = $_GET['y'];

		if ($this->month == '')
			$this->month = kbdate('m');

		if ($this->year == '')
			$this->year = kbdate('Y');

		if ($this->month == 12)
		{
			$this->nmonth = 1;
			$this->nyear = $this->year + 1;
		}
		else
		{
			$this->nmonth = $this->month + 1;
			$this->nyear = $this->year;
		}
		if ($this->month == 1)
		{
			$this->pmonth = 12;
			$this->pyear = $this->year - 1;
		}
		else
		{
			$this->pmonth = $this->month - 1;
			$this->pyear = $this->year;
		}
		$this->monthname = kbdate("F", strtotime("2000-".$this->month."-2"));
		global $smarty;
		$smarty->assign('monthname', $this->monthname);
		$smarty->assign('year', $this->year);
		$smarty->assign('pmonth', $this->pmonth);
		$smarty->assign('pyear', $this->pyear);
		$smarty->assign('nmonth', $this->nmonth);
		$smarty->assign('nyear', $this->nyear);
		$this->alliance = new Alliance($this->all_id);
		if($this->alliance->isFaction()) $this->page = new Page('Faction details - '.$this->alliance->getName());
		else $this->page = new Page('Alliance details - '.$this->alliance->getName());

		$smarty->assign('all_name', $this->alliance->getName());
		$smarty->assign('all_id', $this->alliance->getID());
	}

	function stats()
	{
		global $smarty;
		// The summary table is also used by the stats. Whichever is called
		// first generates the table.
		if(!isset($this->kill_summary))
		{
			$this->kill_summary = new KillSummaryTable();
			$this->kill_summary->addInvolvedAlliance($this->alliance);
			$this->kill_summary->generate();
		}
		if (file_exists("img/alliances/".$this->alliance->getUnique().".png"))
			$smarty->assign('all_img', $this->alliance->getUnique());
		else
			$smarty->assign('all_img', 'default');
		$smarty->assign('totalkills', $this->kill_summary->getTotalKills());
		$smarty->assign('totallosses', $this->kill_summary->getTotalLosses());
		$smarty->assign('totalkisk', round($this->kill_summary->getTotalKillISK()/1000000000, 2));
		$smarty->assign('totallisk', round($this->kill_summary->getTotalLossISK()/1000000000, 2));
		if ($this->kill_summary->getTotalKillISK())
			$smarty->assign('efficiency', round($this->kill_summary->getTotalKillISK() / ($this->kill_summary->getTotalKillISK() + $this->kill_summary->getTotalLossISK()) * 100, 2));
		else
			$smarty->assign('efficiency', '0');
		return $smarty->fetch(get_tpl('alliance_detail_stats'));
	}

	function summaryTable()
	{
		if($this->view != '' && $this->view != 'recent_activity'
			&& $this->view != 'kills' && $this->view != 'losses') return '';
		// The summary table is also used by the stats. Whichever is called
		// first generates the table.
		if(isset($this->kill_summary)) return $this->kill_summary->generate();

		$this->kill_summary = new KillSummaryTable();
		$this->kill_summary->addInvolvedAlliance($this->alliance);
		return $this->kill_summary->generate();
	}

	function killList()
	{
		global $smarty;
		if($this->view == '')
			$smarty->assign('view', 'recent_activity');
		else
			$smarty->assign('view', $this->view);
		switch ($this->view)
		{
			case "":
				$list = new KillList();
				$list->setOrdered(true);
				if (config::get('comments_count')) $list->setCountComments(true);
				if (config::get('killlist_involved')) $list->setCountInvolved(true);
				$list->setLimit(10);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->addInvolvedAlliance($this->alliance);
				if ($this->scl_id)
					$list->addVictimShipClass($this->scl_id);
				//$list->setStartDate(date('Y-m-d H:i',strtotime('- 30 days')));
				$ktab = new KillListTable($list);
				$ktab->setLimit(10);
				$ktab->setDayBreak(false);
				$smarty->assign('killtable', $ktab->generate());

				$list = new KillList();
				$list->setOrdered(true);
				if (config::get('comments_count')) $list->setCountComments(true);
				if (config::get('killlist_involved')) $list->setCountInvolved(true);
				$list->setLimit(10);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->addVictimAlliance($this->alliance);
				if ($this->scl_id)
					$list->addVictimShipClass($this->scl_id);
				//$list->setStartDate(date('Y-m-d H:i',strtotime('- 30 days')));

				$ltab = new KillListTable($list);
				$ltab->setLimit(10);
				$ltab->setDayBreak(false);
				$smarty->assign('losstable', $ltab->generate());

				break;
			case "kills":
				$list = new KillList();
				$list->setOrdered(true);
				$list->addInvolvedAlliance($this->alliance);
				if ($this->scl_id)
					$list->addVictimShipClass($this->scl_id);
				$list->setPageSplit(config::get('killcount'));
				$this->pagesplitter = new PageSplitter($list->getCount(), config::get('killcount'));
				$table = new KillListTable($list);
				$table->setDayBreak(false);
				$smarty->assign('killtable', $table->generate());
				$smarty->assign('splitter', $this->pagesplitter->generate());

				break;
			case "losses":
				$list = new KillList();
				$list->setOrdered(true);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->addVictimAlliance($this->alliance);
				if ($this->scl_id)
					$list->addVictimShipClass($this->scl_id);
				$list->setPageSplit(config::get('killcount'));
				$this->pagesplitter = new PageSplitter($list->getCount(), config::get('killcount'));

				$table = new KillListTable($list);
				$table->setDayBreak(false);
				$smarty->assign('losstable', $table->generate());
				$smarty->assign('splitter', $this->pagesplitter->generate());

				break;
			case "corp_kills":
				$list = new TopCorpKillsList();
				$list->addInvolvedAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->setMonth($this->month);
				$list->setYear($this->year);
				$table = new TopCorpTable($list, "Kills");
				$smarty->assign('killtable', $table->generate());

				$list = new TopCorpKillsList();
				$list->addInvolvedAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$table = new TopCorpTable($list, "Kills");
				$smarty->assign('allkilltable', $table->generate());
				break;
			case "corp_kills_class":
				$html .= "<div class=block-header2>Destroyed ships</div>";

				// Get all ShipClasses
				$sql = "select scl_id, scl_class from kb3_ship_classes
					where scl_class not in ('Drone','Unknown') order by scl_class";

				$qry = new DBQuery();
				$qry->execute($sql);
				while ($row = $qry->getRow())
				{
					$shipclass[] = new Shipclass($row['scl_id']);
				}
				$html .= "<table class='kb-subtable'>";
				$html .= "<tr>";
				$newrow = true;

				foreach ($shipclass as $shp){
					if ($newrow){
					$html .= '</tr><tr>';
					}
					$list = new TopCorpKillsList();
					$list->addInvolvedAlliance($this->alliance);
					$list->addVictimShipClass($shp);
					$table = new TopCorpTable($list, "Kills");
					$content = $table->generate();
					if ($content != '<table class="kb-table" cellspacing=1><tr class="kb-table-header"><td class="kb-table-cell" align=center>#</td><td class="kb-table-cell" align=center>Corporation</td><td class="kb-table-cell" align=center width=60>Kills</td></tr></table>'){
					$html .= "<td valign=top width=440>";
					$html .= "<div class=block-header>".$shp->getName()."</div>";
					$html .= $content;
					$html .= "</td>";
					$newrow = !$newrow;
					}

				}
				$html .= "</tr></table>";
				$smarty->assign('html', $html);
				break;
			case "kills_class":
				$html .= "<div class=block-header2>Destroyed ships</div>";

				// Get all ShipClasses
				$sql = "select scl_id, scl_class from kb3_ship_classes
					where scl_class not in ('Drone','Unknown') order by scl_class";

				$qry = new DBQuery();
				$qry->execute($sql);
				while ($row = $qry->getRow())
				{
					$shipclass[] = new Shipclass($row['scl_id']);
				}
				$html .= "<table class='kb-subtable'>";
				$html .= "<tr>";
				$newrow = true;

				foreach ($shipclass as $shp){
					if ($newrow){
					$html .= '</tr><tr>';
					}
					$list = new TopKillsList();
					$list->addInvolvedAlliance($this->alliance);
					$list->addVictimShipClass($shp);
					$table = new TopPilotTable($list, "Kills");
					$content = $table->generate();
					if ($content != '<table class="kb-table" cellspacing=1><tr class="kb-table-header"><td class="kb-table-cell" align=center colspan=2>Pilot</td><td class="kb-table-cell" align=center width=60>Kills</td></tr></table>'){
					$html .= "<td valign=top width=440>";
					$html .= "<div class=block-header>".$shp->getName()."</div>";
					$html .= $content;
					$html .= "</td>";
					$newrow = !$newrow;
					}

				}
				$html .= "</tr></table>";
				$smarty->assign('html', $html);

				break;
			case "corp_losses_class":
				$html .= "<div class=block-header2>Lost ships</div>";

					// Get all ShipClasses
				$sql = "select scl_id, scl_class from kb3_ship_classes
					where scl_class not in ('Drone','Unknown') order by scl_class";

				$qry = new DBQuery();
				$qry->execute($sql);
				while ($row = $qry->getRow())
				{
					$shipclass[] = new Shipclass($row['scl_id']);
				}
				$html .= "<table class='kb-subtable'>";
				$html .= "<tr>";
				$newrow = true;

				foreach ($shipclass as $shp){
					if ($newrow){
					$html .= '</tr><tr>';
					}
					$list = new TopCorpLossesList();
						$list->addVictimAlliance($this->alliance);
					$list->addVictimShipClass($shp);
					$table = new TopCorpTable($list, "Losses");
					$content = $table->generate();
					if ($content != '<table class="kb-table" cellspacing=1><tr class="kb-table-header"><td class="kb-table-cell" align=center>#</td><td class="kb-table-cell" align=center>Corporation</td><td class="kb-table-cell" align=center width=60>Losses</td></tr></table>'){
					$html .= "<td valign=top width=440>";
						$html .= "<div class=block-header>".$shp->getName()."</div>";
						$html .= $content;
					$html .= "</td>";
					$newrow = !$newrow;
					}
				}
				$html .= "</tr></table>";
				$smarty->assign('html', $html);

				break;
			case "losses_class":
				$html .= "<div class=block-header2>Lost ships</div>";

					// Get all ShipClasses
				$sql = "select scl_id, scl_class from kb3_ship_classes
					where scl_class not in ('Drone','Unknown') order by scl_class";

				$qry = new DBQuery();
				$qry->execute($sql);
				while ($row = $qry->getRow())
				{
					$shipclass[] = new Shipclass($row['scl_id']);
				}
				$html .= "<table class='kb-subtable'>";
				$html .= "<tr>";
				$newrow = true;

				foreach ($shipclass as $shp){
					if ($newrow){
					$html .= '</tr><tr>';
					}
					$list = new TopLossesList();
						$list->addVictimAlliance($this->alliance);
					$list->addVictimShipClass($shp);
					$table = new TopPilotTable($list, "Losses");
					$content = $table->generate();
					if ($content != '<table class="kb-table" cellspacing=1><tr class="kb-table-header"><td class="kb-table-cell" align=center colspan=2>Pilot</td><td class="kb-table-cell" align=center width=60>Losses</td></tr></table>'){
					$html .= "<td valign=top width=440>";
						$html .= "<div class=block-header>".$shp->getName()."</div>";
						$html .= $content;
					$html .= "</td>";
					$newrow = !$newrow;
					}
				}
				$html .= "</tr></table>";
				$smarty->assign('html', $html);

				break;
			case "corp_losses":
				$list = new TopCorpLossesList();
				$list->addVictimAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->setMonth($this->month);
				$list->setYear($this->year);
				$table = new TopCorpTable($list, "Losses");
				$smarty->assign('losstable', $table->generate());

				$list = new TopCorpLossesList();
				$list->addVictimAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$table = new TopCorpTable($list, "Losses");
				$smarty->assign('alllosstable', $table->generate());
				break;
			case "pilot_kills":
				$html .= "<div class=block-header2>Top killers</div>";

				$html .= "<table class='kb-subtable'><tr><td valign=top width=440>";
				$html .= "<div class=block-header>$this->monthname $this->year</div>";

				$list = new TopKillsList();
				$list->addInvolvedAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->setMonth($this->month);
				$list->setYear($this->year);
				$table = new TopPilotTable($list, "Kills");
				$html .= $table->generate();

				$html .= "<table width=300 cellspacing=1><tr><td><a href='?a=alliance_detail&amp;view=pilot_kills&amp;m=$this->pmonth&amp;all_id=$this->all_id&amp;y=$this->pyear'>previous</a></td>";
				$html .= "<td align='right'><a href='?a=alliance_detail&amp;view=pilot_kills&amp;all_id=$this->all_id&amp;m=$this->nmonth&amp;y=$this->nyear'>next</a></td></tr></table>";

				$html .= "</td><td valign=top width=400>";
				$html .= "<div class=block-header>All time</div>";

				$list = new TopKillsList();
				$list->addInvolvedAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$table = new TopPilotTable($list, "Kills");
				$html .= $table->generate();

				$html .= "</td></tr></table>";
				$smarty->assign('html', $html);

				break;
			case "pilot_scores":
				$html .= "<div class=block-header2>Top scorers</div>";

				$html .= "<table class='kb-subtable'><tr><td valign=top width=440>";
				$html .= "<div class=block-header>$this->monthname $this->year</div>";

				$list = new TopScoreList();
				$list->addInvolvedAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->setMonth($this->month);
				$list->setYear($this->year);
				$table = new TopPilotTable($list, "Points");
				$html .= $table->generate();

				$html .= "<table width=300 cellspacing=1><tr><td><a href='?a=alliance_detail&amp;view=pilot_scores&amp;m=$this->pmonth&amp;all_id=$this->all_id&amp;y=$this->pyear'>previous</a></td>";
				$html .= "<td align='right'><a href='?a=alliance_detail&amp;view=pilot_scores&amp;all_id=$this->all_id&amp;m=$this->nmonth&amp;y=$this->nyear'>next</a></td></tr></table>";

				$html .= "</td><td valign=top width=400>";
				$html .= "<div class=block-header>All time</div>";

				$list = new TopScoreList();
				$list->addInvolvedAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$table = new TopPilotTable($list, "Points");
				$html .= $table->generate();

				$html .= "</td></tr></table>";
				$smarty->assign('html', $html);

				break;
			case "pilot_losses":
				$list = new TopLossesList();
				$list->addVictimAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$list->setMonth($this->month);
				$list->setYear($this->year);
				$table = new TopPilotTable($list, "Losses");
				$smarty->assign('losstable', $table->generate());

				$list = new TopLossesList();
				$list->addVictimAlliance($this->alliance);
				$list->setPodsNoobShips(config::get('podnoobs'));
				$table = new TopPilotTable($list, "Losses");
				$smarty->assign('totallosstable', $table->generate());

				break;
			case "ships_weapons":
				$view = "ships_weapons";
				$shiplist = new TopShipList();
				$shiplist->addInvolvedAlliance($this->alliance);
				$shiplisttable = new TopShipListTable($shiplist);
				$smarty->assign('shiplisttable', $shiplisttable->generate());

				$weaponlist = new TopWeaponList();
				$weaponlist->addInvolvedAlliance($this->alliance);
				$weaponlisttable = new TopWeaponListTable($weaponlist);
				$smarty->assign('weaponlisttable', $weaponlisttable->generate());

				break;
			case 'violent_systems':
				$html .= "<div class=block-header2>Most violent systems</div>";
				$html .= "<table width=\"99%\"><tr><td align=center valign=top>";

				$html .= "<div class=block-header>$this->monthname, $this->year</div>";
				$html .= "<table class='kb-table'>";
				$html .= "<tr class='kb-table-header'><td>#</td><td width=180>System</td><td width=40 align=center >Kills</td></tr>";

				$sql = "select sys.sys_name, sys.sys_sec, sys.sys_id, count(ina.ina_kll_id) as kills
							from kb3_systems sys, kb3_kills kll, kb3_inv_all ina
							where kll.kll_system_id = sys.sys_id
							and ina.ina_all_id = $this->all_id
							and ina.ina_kll_id = kll.kll_id";

				$sql .= " and ina.ina_timestamp > '".
					gmdate('Y-m-d H:i', makeStartDate(0, $this->year, $this->month))."'";
				$sql .= " and ina.ina_timestamp < '".
					gmdate('Y-m-d H:i', makeEndDate(0, $this->year, $this->month))."'";

				$sql .= "   group by sys.sys_name
							order by kills desc
							limit 25";

				$qry = new DBQuery();
				$qry->execute($sql);
				$odd = false;
				$counter = 1;
				while ($row = $qry->getRow())
				{
					if (!$odd)
					{
						$odd = true;
						$rowclass = 'kb-table-row-odd';
					}
					else
					{
						$odd = false;
						$rowclass = 'kb-table-row-even';
					}

					$html .= "<tr class=".$rowclass."><td><b>".$counter.".</b></td><td class='kb-table-cell' width=180><b><a href=\"?a=system_detail&amp;sys_id=".$row['sys_id']."\">".$row['sys_name']."</a></b> (".roundsec($row['sys_sec']).")</td><td align=center>".$row['kills']."</td></tr>";
					$counter++;
				}

				$html .= "</table>";

				$html .= "<table width=250 cellspacing=1><tr><td><a href='?a=alliance_detail&amp;view=violent_systems&amp;m=$this->pmonth&amp;all_id=$this->all_id&amp;y=$this->pyear'>previous</a></td>";
				$html .= "<td align='right'><a href='?a=alliance_detail&amp;view=violent_systems&amp;all_id=$this->all_id&amp;m=$this->nmonth&amp;y=$this->nyear'>next</a></td></tr></table>";
				$html .= "</td><td align=center valign=top>";
				$html .= "<div class=block-header>All-Time</div>";
				$html .= "<table class='kb-table'>";
				$html .= "<tr class='kb-table-header'><td>#</td><td width=180>System</td><td width=40 align=center>Kills</td></tr>";

				$sql = "select sys.sys_name, sys.sys_id, sys.sys_sec, count(kll.kll_id) as kills
							from kb3_systems sys, kb3_kills kll, kb3_inv_all ina
							where kll.kll_system_id = sys.sys_id
							and ina.ina_all_id = $this->all_id
							and ina.ina_kll_id = kll.kll_id
							group by sys.sys_name
							order by kills desc
							limit 25";

				$qry = new DBQuery();
				$qry->execute($sql);
				$odd = false;
				$counter = 1;
				while ($row = $qry->getRow())
				{
					if (!$odd)
					{
						$odd = true;
						$rowclass = 'kb-table-row-odd';
					}
					else
					{
						$odd = false;
						$rowclass = 'kb-table-row-even';
					}

					$html .= "<tr class=".$rowclass."><td><b>".$counter.".</b></td><td class='kb-table-cell'><b><a href=\"?a=system_detail&amp;sys_id=".$row['sys_id']."\">".$row['sys_name']."</a></b> (".roundsec($row['sys_sec']).")</td><td align=center>".$row['kills']."</td></tr>";
					$counter++;
				}
				$html .= "</table>";
				$html .= "</td></tr></table>";
				$smarty->assign('html', $html);
			break;
		}
		return $smarty->fetch(get_tpl('alliance_detail'));
	}

	function context()
	{
		parent::__construct();
		$this->queue("menu");
	}

	function menu()
	{
		$menubox = new Box("Menu");
		$menubox->setIcon("menu-item.gif");
		$menubox->addOption("caption","Kills &amp; losses");
		$menubox->addOption("link","Recent activity", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID());
		$menubox->addOption("link","Kills", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=kills");
		$menubox->addOption("link","Losses", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=losses");
		$menubox->addOption("caption","Corp statistics");
		$menubox->addOption("link","Top killers", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=corp_kills");
		$menubox->addOption("link","Top losers", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=corp_losses");
		$menubox->addOption("link","Destroyed ships", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=corp_kills_class");
		$menubox->addOption("link","Lost ships", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=corp_losses_class");
		$menubox->addOption("caption","Pilot statistics");
		$menubox->addOption("link","Top killers", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=pilot_kills");
		if (config::get('kill_points'))
		{
			$menubox->addOption('link', "Top scorers", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=pilot_scores");
		}
		$menubox->addOption("link","Top losers", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=pilot_losses");
		$menubox->addOption("link","Destroyed ships", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=kills_class");
		$menubox->addOption("link","Lost ships", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=losses_class");
		$menubox->addOption("caption","Global statistics");
		$menubox->addOption("link","Ships &amp; weapons", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=ships_weapons");
		$menubox->addOption("link","Most violent systems", "?a=alliance_detail&amp;all_id=" . $this->alliance->getID() . "&amp;view=violent_systems");
		foreach($this->menuOptions as $options)
		{
			if(isset($options[2]))
				$menubox->addOption($options[0],$options[1], $options[2]);
			else
				$menubox->addOption($options[0],$options[1]);
		}
		return $menubox->generate();
	}
}

$allianceDetail = new pallianceDetail();
event::call("allianceDetail_assembling", $allianceDetail);
$html = $allianceDetail->assemble();
$allianceDetail->page->setContent($html);

$allianceDetail->context();
event::call("allianceDetail_context_assembling", $allianceDetail);
$context = $allianceDetail->assemble();
$allianceDetail->page->addContext($context);

$allianceDetail->page->generate();