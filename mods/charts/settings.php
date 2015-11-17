<?php
require_once("common/admin/admin_menu.php");
$page = new Page('Charts - Settings');

if (isset($_POST['save'])) {
  config::set('charts_front_page', $_POST["front_page"]);
  config::set('charts_alliance_page', $_POST["alliance_page"]);
  config::set('charts_corp_page', $_POST["corp_page"]);
  config::set('charts_pilot_page', $_POST["pilot_page"]);
  
  $msg .= "Settings updated!";
}

if ($msg) {
	$html .= "<span style='color:green'>".$msg."</span><br />";
}

$html .= "<div class='block-header2'>Charts locations.</div>";
$html .= "<form method='post' action='?a=settings_charts'>";
$html .= "<input type='checkbox' id='front_page' name='front_page'";
$html .= (Config::get('charts_front_page') ? " checked='checked'": "")." />";
$html .= " Front Page.<br />";
$html .= "<input type='checkbox' id='alliance_page' name='alliance_page'";
$html .= (Config::get('charts_alliance_page') ? " checked='checked'": "")." />";
$html .= " Alliance Page.<br />";
$html .= "<input type='checkbox' id='corp_page' name='corp_page'";
$html .= (Config::get('charts_corp_page') ? " checked='checked'": "")." />";
$html .= " Corp Page.<br />";
$html .= "<input type='checkbox' id='pilot_page' name='pilot_page'";
$html .= (Config::get('charts_pilot_page') ? " checked='checked'": "")." />";
$html .= " Pilot Page.<br />";
$html .= "<br /><input type='submit' name='save' value='Save' />";
$html .= "</form>";

$page->setContent($html);
$page->addContext($menubox->generate());
$page->generate();
