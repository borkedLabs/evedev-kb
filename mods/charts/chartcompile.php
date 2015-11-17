<?php

/**
 * Based on FusionCharts implementation by Xeltor02
 */
 
/**
 * @return array kills (dati per il grafico)
 * @param integer year (four digits) 
 * @param integer week of the year (1..53)
 * @desc This function retrieves the data (kills and losses) in given interval, for the graph.
 */
function chartsGenerateHome($week, $year)
{
	if(Config::get('show_monthly')) 
	{
		$_y = (int)edkURI::getArg('y', 1);
		$_m = (int)edkURI::getArg('m', 2);
		if(!$_y && !$_m) 
		{
			$today = getdate();
			$days = date("t");
		} 
		else 
		{
			$days = date("t");
		}
		
		for ($day=1; $day <= $days; $day++)
		{
			if(!$_y && !$_m) 
			{
				$giorno = $today["year"]."-".$today["mon"]."-".$day;
			} 
			else 
			{
				$giorno = $_y."-".$_m."-".$day;
			}

			$kills[$day][1]=date("j", strtotime(($giorno)));
		
			if ( config::get('cfg_allianceid') || config::get('cfg_corpid') || config::get('cfg_pilotid') )
			{
				chartsTotalLoss($giorno, $giorno, $cnt, $valore);
				$kills[$day][2]=$cnt;
				$kills[$day][4]=$valore;

				chartsTotalKills($giorno, $giorno, $cnt, $valore);
				$kills[$day][3]=$cnt;
				$kills[$day][5]=$valore;
			} 
			else
			{
				chartsTotalPublic($giorno, $giorno, $cnt, $valore);
				$kills[$day][2]=$cnt;
				$kills[$day][4]=$valore;
			}
		}
	} 
	else 
	{
		for ($day=0; $day < 7; $day++)
		{
			$giorno = mktimefromcw($year, $week, $day);

			$kills[$day][1]=date("l", strtotime(($giorno)));

			if ( config::get('cfg_allianceid') || config::get('cfg_corpid') || config::get('cfg_pilotid') )
			{
				chartsTotalLoss($giorno, $giorno, $cnt, $valore);
				$kills[$day][2]=$cnt;
				$kills[$day][4]=$valore;

				chartsTotalKills($giorno, $giorno, $cnt, $valore);
				$kills[$day][3]=$cnt;
				$kills[$day][5]=$valore;
			} 
			else 
			{
				chartsTotalPublic($giorno, $giorno, $cnt, $valore);
				$kills[$day][2]=$cnt;
				$kills[$day][4]=$valore;
			}
		}
	}
	
	return $kills;
}

function chartGeneratePages($dtl)
{
	$week = date('W');
	$year = date('Y');
	if(Config::get('show_monthly')) 
	{
		$today = getdate();
		$days = date("t");
		
		for ($day=1; $day <= $days; $day++)
		{
			$giorno = $today["year"]."-".$today["mon"]."-".$day;

			$kills[$day][1]=date("j", strtotime(($giorno)));

			chartsTotalLoss($giorno, $giorno, $cnt, $valore, $dtl);
			$kills[$day][2]=$cnt;
			$kills[$day][4]=$valore;

			chartsTotalKills($giorno, $giorno, $cnt, $valore, $dtl);
			$kills[$day][3]=$cnt;
			$kills[$day][5]=$valore;
		}
	} 
	else 
	{
    	for ($day=0; $day < 7; $day++)
        {
	        $giorno = mktimefromcw($year, $week, $day);
	        
	        $kills[$day][1] = date("l", strtotime(($giorno)));
	
	        chartsTotalLoss($giorno, $giorno, $cnt, $valore, $dtl);
	        $kills[$day][2]=$cnt;
	        $kills[$day][4]=$valore;
	
	        chartsTotalKills($giorno, $giorno, $cnt, $valore, $dtl);
	        $kills[$day][3]=$cnt;
	        $kills[$day][5]=$valore;
        }
	}
	
    return $kills;
}
    
function chartsTotalKills($startDay, $endDay, &$cnt, &$valore, $dtl = 0)
{
	$qry = new DBQuery();
	if($dtl != 0) 
	{
		$allianceID = $dtl->all_id;
		$corpID = $dtl->crp_id;
		$pilotID = $dtl->plt_id;
		
		if($allianceID) 
		{
			$sql = "
								SELECT 
									COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
								FROM 
									kb3_kills 
								WHERE 
									(kll_all_id = " . $allianceID . ") AND
									(kll_timestamp >= '$startDay 00:00:00') AND
									(kll_timestamp <= '$endDay 23:59:59')";
		} 
		else if($corpID) 
		{
			$sql = "
								SELECT 
									COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
								FROM 
									kb3_kills 
								WHERE 
									(kll_crp_id = " . $corpID . ") AND
									(kll_timestamp >= '$startDay 00:00:00') AND
									(kll_timestamp <= '$endDay 23:59:59')";
		} 
		else if($pilotID) 
		{
			$sql = "
								SELECT 
									COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
								FROM 
									kb3_kills 
								WHERE 
									(kll_victim_id = " . $pilotID . ") AND
									(kll_timestamp >= '$startDay 00:00:00') AND
									(kll_timestamp <= '$endDay 23:59:59')";
		}
	} 
	else 
	{
		$allianceID = config::get('cfg_allianceid');
		$corpID = config::get('cfg_corpid');
		$pilotID = config::get('cfg_pilotid');
		
		$sql = "
				SELECT 
					COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
				FROM 
					kb3_kills 
				WHERE ";
		$sql .= "(";
		
		if(count($allianceID)) 
		{
			if(count($allianceID)==1) 
			{
				$sql .= "(kll_all_id = " . $allianceID[0] . ")";
			} 
			else 
			{
				for($i = 0; $i < (count($allianceID)-1); ++$i)
				{
					$sql .= "(kll_all_id = " . $allianceID[$i] . ") OR";
				}
				$sql .= "(kll_all_id = " . $allianceID[$i] . ")";
			}
		}
		if(count($corpID))
		{
			if(count($allianceID)) $sql .= "OR";
			
			if(count($corpID)==1)
			{
				$sql .= "(kll_crp_id = " . $corpID[0] . ")";
			} 
			else 
			{
				for($i = 0; $i < (count($corpID)-1); ++$i)
				{
					$sql .= "(kll_crp_id = " . $corpID[$i] . ") OR";
				}
				$sql .= "(kll_crp_id = " . $corpID[$i] . ")";
			}
		}
		if(count($pilotID))
		{
			if(count($corpID) || count($allianceID)) $sql .= "OR";
			
			if(count($pilotID)==1)
			{
				$sql .= "(kll_victim_id = " . $pilotID[0] . ")";
			} 
			else 
			{
				for($i = 0; $i < (count($pilotID)-1); ++$i)
				{
					$sql .= "(kll_victim_id = " . $pilotID[$i] . ") OR";
				}
				$sql .= "(kll_victim_id = " . $pilotID[$i] . ")";
			}
		}
		
		$sql .= ") AND (kll_timestamp >= '$startDay 00:00:00') AND
									(kll_timestamp <= '$endDay 23:59:59')";
		
	}
	$qry->execute($sql)
		or die($qry->getErrorMsg());
	$row = $qry->getRow();

	$cnt = $row['cnt'];
	$valore=round( $row['valore'] / 1000000, 2 );
}
	
function chartsTotalLoss($startDay, $endDay, &$cnt, &$valore, $dtl = 0)
{
	$qry  =new DBQuery();
	if($dtl != 0) 
	{
		$allianceID = $dtl->all_id;
		$corpID = $dtl->crp_id;
		$pilotID = $dtl->plt_id;
		if($allianceID) 
		{
			$sql = "			SELECT 
									COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
								FROM 
									kb3_kills
								LEFT JOIN
									kb3_inv_all ON kb3_kills.kll_id=kb3_inv_all.ina_kll_id
								WHERE 
									(kb3_inv_all.ina_all_id = " . $allianceID . ") AND
									(kb3_kills.kll_all_id != " . $allianceID . ") AND
									(kb3_kills.kll_timestamp >= '$startDay 00:00:00') AND
									(kb3_kills.kll_timestamp <= '$startDay 23:59:59')";
		} 
		else if($corpID) 
		{
			$sql = "			SELECT 
									COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
								FROM 
									kb3_kills
								LEFT JOIN
									kb3_inv_crp ON kb3_kills.kll_id=kb3_inv_crp.inc_kll_id
								WHERE 
									(kb3_inv_crp.inc_crp_id = " . $corpID . ") AND
									(kb3_kills.kll_crp_id != " . $corpID . ") AND
									(kb3_kills.kll_timestamp >= '$startDay 00:00:00') AND
									(kb3_kills.kll_timestamp <= '$startDay 23:59:59')";
		} 
		else if($pilotID) 
		{
			$sql = "			SELECT 
									COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
								FROM 
									kb3_kills
								LEFT JOIN
									kb3_inv_detail ON kb3_kills.kll_id=kb3_inv_detail.ind_kll_id
								WHERE 
									(kb3_inv_detail.ind_plt_id = " . $pilotID . ") AND
									(kb3_kills.kll_victim_id != " . $pilotID . ") AND
									(kb3_kills.kll_timestamp >= '$startDay 00:00:00') AND
									(kb3_kills.kll_timestamp <= '$endDay 23:59:59')";
		}
	} 
	else 
	{
		$allianceID = config::get('cfg_allianceid');
		$corpID = config::get('cfg_corpid');
		$pilotID = config::get('cfg_pilotid');
		if(count($allianceID)>0) 
		{
			$sql = "
				SELECT 
					COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
				FROM 
					kb3_kills
				LEFT JOIN
					kb3_inv_all ON kb3_kills.kll_id=kb3_inv_all.ina_kll_id
				WHERE (";
			if(count($allianceID)<2) 
			{
				$sql .= "((kb3_inv_all.ina_all_id = " . $allianceID[0] . ") AND (kb3_kills.kll_all_id != " . $allianceID[0] . "))";
			} 
			else 
			{
				for($i = 0; $i < (count($allianceID)-1); ++$i)
				{
					$sql .= "((kb3_inv_all.ina_all_id = " . $allianceID[$i] . ") AND (kb3_kills.kll_all_id != " . $allianceID[$i] . ")) OR";
				}
				$sql .= "((kb3_inv_all.ina_all_id = " . $allianceID[$i] . ") AND (kb3_kills.kll_all_id != " . $allianceID[$i] . "))";
			}
		} 
		else if(count($corpID)>0) 
		{
			$sql = "
				SELECT 
					COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
				FROM 
					kb3_kills
				LEFT JOIN
					kb3_inv_crp ON kb3_kills.kll_id=kb3_inv_crp.inc_kll_id
				WHERE (";
				
			if(count($corpID)<2) 
			{
				$sql .= "((kb3_inv_crp.inc_crp_id = " . $corpID[0] . ") AND (kb3_kills.kll_crp_id != " . $corpID[0] . "))";
			} 
			else 
			{
				for($i = 0; $i < (count($corpID)-1); ++$i)
				{
					$sql .= "((kb3_inv_crp.inc_crp_id = " . $corpID[$i] . ") AND (kb3_kills.kll_crp_id != " . $corpID[$i] . ")) OR";
				}
				$sql .= "((kb3_inv_crp.inc_crp_id = " . $corpID[$i] . ") AND (kb3_kills.kll_crp_id != " . $corpID[$i] . "))";
			}
		} 
		else if(count($pilotID)>0) 
		{
			$sql = "
				SELECT 
					COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
				FROM 
					kb3_kills
				LEFT JOIN
					kb3_inv_detail ON kb3_kills.kll_id=kb3_inv_detail.ind_kll_id
				WHERE (";
				
			if(count($pilotID)<2) 
			{
				$sql .= "((kb3_inv_detail.ind_plt_id = " . $pilotID[0] . ") AND (kb3_kills.kll_victim_id != " . $pilotID[0] . "))";
			} 
			else 
			{
				for($i = 0; $i < (count($pilotID)-1); ++$i)
				{
					$sql .= "((kb3_inv_detail.ind_plt_id = " . $pilotID[$i] . ") AND (kb3_kills.kll_victim_id != " . $pilotID[$i] . ")) OR";
				}
				$sql .= "((kb3_inv_detail.ind_plt_id = " . $pilotID[$i] . ") AND (kb3_kills.kll_victim_id != " . $pilotID[$i] . "))";
			}
		}
		
		$sql .= ") AND (kll_timestamp >= '$startDay 00:00:00') AND (kll_timestamp <= '$endDay 23:59:59')";
	}
	
	$qry->execute($sql) or die($qry->getErrorMsg());

    $row = $qry->getRow();

    $cnt = $row['cnt'];
    $valore = round( $row['valore'] / 1000000, 2 );
}

function chartsTotalPublic($startDay, $endDay, &$cnt, &$valore, $dtl = 0)
{
	$qry  =new DBQuery();
	$sql = "
			SELECT 
				COUNT(kll_id) AS cnt, SUM(kll_isk_loss) AS valore
			FROM 
				kb3_kills 
			WHERE 
				(kll_timestamp >= '$startDay 00:00:00') AND
				(kll_timestamp <= '$endDay 23:59:59')";
	$qry->execute($sql)
		or die($qry->getErrorMsg());
	$row = $qry->getRow();

	$cnt = $row['cnt'];
	$valore = round( $row['valore'] / 1000000, 2 );
}
	
function chartGenerateHTML($arrData)
{
	$html = "<script type='text/javascript'>";
	
	$killObject = new stdClass;
	$killObject->type = 'bar';
	$killObject->label = 'Kills';
	$killObject->backgroundColor = '#00AA00';
	$killObject->data = [];
	$killObject->yAxisID = 'y-axis-1';
	
	$lossObject = new stdClass;
	$lossObject->type = 'bar';
	$lossObject->label = 'Losses';
	$lossObject->backgroundColor = '#F90000';
	$lossObject->data = [];
	$lossObject->yAxisID = 'y-axis-1';
	
	$iskKillObject = new stdClass;
	$iskKillObject->type = 'line';
	$iskKillObject->label = 'Isk Destroyed';
	$iskKillObject->backgroundColor = 'transparent';
	$iskKillObject->borderColor = '#007A00';
	$iskKillObject->data = [];
	$iskKillObject->yAxisID = 'y-axis-2';
	$iskKillObject->pointBackgroundColor  = 'white';
	$iskKillObject->pointBorderWidth  = 1;
	
	$iskLossObject = new stdClass;
	$iskLossObject->type = 'line';
	$iskLossObject->label = 'Isk Lost';
	$iskLossObject->backgroundColor = 'transparent';
	$iskLossObject->borderColor = '#C90000';
	$iskLossObject->data = [];
	$iskLossObject->yAxisID = 'y-axis-2';
	$iskLossObject->pointBackgroundColor  = 'white';
	$iskLossObject->pointBorderWidth  = 1;
	
	$barData = [
					'datasets' => [
						$killObject,
						$lossObject,
						$iskKillObject,
						$iskLossObject
					]
				];
    foreach ($arrData as $arSubData)
	{
		$barData['labels'][] = $arSubData[1];
		$killObject->data[] = $arSubData[2];
		$lossObject->data[] = $arSubData[3];
		$iskKillObject->data[] = $arSubData[4];
		$iskLossObject->data[] = $arSubData[5];
	}
	
	$html .= "var barChartData = ". json_encode($barData) . ";";
	$html .= '
        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: "bar",
                data: barChartData,
                options: {
                    responsive: false,
					scales: {
						xAxes: [{
							display: true,
							scaleLabel: {
								fontColor: "#fff",
								fontFamily: "Arial",
								show: true,
								labelString: "Day"
							},
							ticks: {
								fontColor: "#fff",
								fontFamily: "Arial"
							},
							gridLines: {
								color: "#454545",
							},
						}],
						yAxes:  [{
									type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
									display: true,
									position: "left",
									id: "y-axis-1",
									scaleLabel: {
										fontColor: "#fff",
										fontFamily: "Arial",
										show: true,
										labelString: "Quantity"
									},
									ticks: {
										fontColor: "#fff",
										fontFamily: "Arial"
									},
									gridLines: {
										color: "#454545",
									},
								}, 
								{
									type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
									display: true,
									position: "right",
									id: "y-axis-2",
									scaleLabel: {
										fontColor: "#fff",
										fontFamily: "Arial",
										show: true,
										labelString: "ISK (M)"
									},
									ticks: {
										fontColor: "#fff",
										fontFamily: "Arial"
									},
									// grid line settings
									gridLines: {
										drawOnChartArea: false, // only want the grid lines for one axis to show up
									},
								}]
					}
                }
            });
        };';
	$html .= "</script>";
	$html .= "<div width='100%' style='display:block;background-color:#292929;padding-top:20px;'><canvas id='canvas' height='200px' width='720px'></canvas></div>";
	
    $html.='<script type="text/javascript" src="'.config::get("cfg_kbhost").'/mods/charts/charts/moment.min.js"></script>';
    $html.='<script type="text/javascript" src="'.config::get("cfg_kbhost").'/mods/charts/charts/Chart.min.js"></script>';

    return $html;
}

/**
 * @return MySQL Date (string)
 * @param integer year (four digits) 
 * @param integer week of the year (1..53)
 * @param integer day of the week (1..7)
 * @desc This function retrieves the time for the given year, week of year and day of the week and returns it.
 */
function mktimefromcw($year, $woy = 1, $dow = 1)
{
    $dow           =($dow) % 7;
    $woy           =($woy)-1;

    # Get reference value (this is the first monday of the first week of the year, not easy to calculate)
    $fdoy_timestamp=mktime(0, 0, 0, 1, 1, $year);
    $fdoy          =((date("w", $fdoy_timestamp) + 6) % 7) + 1;

    if ($fdoy == 1)
	{
        # This first day of the year is a monday
        $fcwstart=$fdoy_timestamp;
	}
    elseif ($fdoy < 5)
	{
        # The first day if before Friday, therefor the first Monday can be found in the previos year (this is no fun, believe in it!).
        $fcwstart=strtotime("last Monday", $fdoy_timestamp);
	}
    else
	{
        # The first day is a friday or later, so the first days belong to calender week 53 (yes, this is possible!) of the previous year, do not count them for this year.
        $fcwstart=strtotime("next Monday", $fdoy_timestamp);
	}

    # Create timestamp
    $timestr=date("d F Y", $fcwstart) . " +$woy week +$dow day";
    //$timestr = date("Y-m-d H:i:s", $fcwstart)." +$woy week +$dow day";
    $time   =strtotime($timestr);
    //$time = $timestr;

    # Return timestamp
    return date("Y-m-d", $time);
}