<?php

$nochart = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'EVE-IGB');
$nochart = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
$nochart = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');

if (!$nochart)
{
	if(Config::get('charts_front_page')) {
		event::register('home_assembling', 'charts::addHome');
	}
	if(Config::get('charts_alliance_page')) {
		event::register('allianceDetail_assembling', 'charts::addAllianceDetail');
	}
	if(Config::get('charts_corp_page')) {
		event::register('corpDetail_assembling', 'charts::addCorpDetail');
	}
	if(Config::get('charts_pilot_page')) {
		event::register('pilotDetail_assembling', 'charts::addPilotDetail');
	}
}

class charts
{
	public static function addCorpDetail($home)
    {
        $home->addBehind("summaryTable", "charts::graphPages");
    }
    
    public static function addPilotDetail($home)
    {
        $home->addBehind("summaryTable", "charts::graphPages");
    }
	
    public static function addAllianceDetail($home)
    {
        $home->addBehind("summaryTable", "charts::graphPages");
    }
	
    public static function addHome($home)
    {
        $home->addBehind("summaryTable", "charts::graphHome");
    }
    
    public static function graphHome($home)
    {
		require_once('mods/charts/chartcompile.php');

		$html = chartGenerateHTML(chartsGenerateHome($home->getWeek(), $home->getYear()));
        return $html;
    }
	
	public static function graphPages($home)
    {
		require_once('mods/charts/chartcompile.php');

		$html = chartGenerateHTML(chartGeneratePages($home));
        return $html;
    }
}