<?php

/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
 
use EDK\Core\Config;
use EDK\Core\URI;
use EDK\Database;
use EDK\Entity\Corporation;
use EDK\PageComponent\Options;

Options::cat('Advanced', 'Configuration', 'Available updates');
Options::fadd('Code updates', 'none', 'custom', array('update', 'codeCheck'));

Options::cat('Advanced', 'Configuration', 'Killboard Configuration');
Options::fadd('Killboard Title', 'cfg_kbtitle', 'edit:size:50');
Options::fadd('Main Webpage Link', 'cfg_mainsite', 'edit:size:50');
Options::fadd('Killboard Host', 'cfg_kbhost', 'edit:size:50', '',
		array('admin_config', 'checkHost'));
Options::fadd('Image base URL', 'cfg_img', 'edit:size:50', '',
		array('admin_config', 'checkImg'));
Options::fadd('Use CCP imageserver', 'cfg_ccpimages', 'checkbox');
Options::fadd('Use simple URLs', 'cfg_pathinfo', 'checkbox', '', '',
		'e.g. '.KB_HOST.'/index.php/kill_details/1234/');
Options::fadd('Allow Masterfeed', 'feed_allowmaster', 'checkbox');
Options::fadd('Compress pages', 'cfg_compress', 'checkbox', '', '',
		'Enable unless you encounter errors');
Options::fadd('Display profiling information', 'cfg_profile', 'checkbox');
Options::fadd('Log errors', 'cfg_log', 'checkbox');
Options::fadd('Lock board', 'cfg_locked', 'checkbox');

Options::cat('Advanced', 'Configuration', 'API');
Options::fadd('API connection method', 'apiConnectionMethod', 'select',
		array('admin_config', 'createApiConnectionMethod'));

Options::cat('Advanced', 'Configuration', 'Public-Mode');
Options::fadd('Only Kills in SummaryTables', 'public_summarytable', 'checkbox',
		'', '', 'Set no board owners to work in public mode');
Options::fadd('Remove Losses Page', 'public_losses', 'checkbox');
Options::fadd('Stats Page', 'public_stats', 'select',
		array('admin_config', 'createSelectStats'));

Options::cat('Advanced', 'Configuration', 'Pilot/Corp/Alliance ID (Provide'
		.' either exact full name, ID or external ID)');
Options::fadd('Add Pilot', '', 'custom', array('admin_config', 'createPilot'));
Options::fadd('Add Corporation', '', 'custom', array('admin_config',
	'createCorp'));
Options::fadd('Add Alliance', '', 'custom', array('admin_config',
	'createAlliance'));

if (Config::get('cfg_pilotid')) {
	Options::fadd('Remove Pilot', 'rem_pilotid', 'select',
			array('admin_config', 'removePilot'));
}
if (Config::get('cfg_corpid')) {
	Options::fadd('Remove Corporation', 'rem_corpid', 'select',
			array('admin_config', 'removeCorp'));
}
if (Config::get('cfg_allianceid')) {
	Options::fadd('Remove Alliance', 'rem_allianceid', 'select',
			array('admin_config', 'removeAlliance'));
}

class admin_config
{

	public static function checkHost()
	{
		if (!isset($_POST['option_cfg_kbhost'])) {
			return;
		}
		$newhost = preg_replace('/\/+$/', '', $_POST['option_cfg_kbhost']);
		Config::set('cfg_kbhost', $newhost);
		$_POST['option_cfg_kbhost'] = $newhost;
	}

	public static function checkImg()
	{
		if (!isset($_POST['option_cfg_img'])) {
			return;
		}
		$newimg = preg_replace('/\/+$/', '', $_POST['option_cfg_img']);
		Config::set('cfg_img', $newimg);
		$_POST['option_cfg_img'] = $newimg;
	}
        
        public static function createApiConnectionMethod()
	{
		$options = array();
                \EDK\EVEAPI\Helpers::autoSetApiConnectionMethod();

		if (Config::get('apiConnectionMethod') == 'curl') {
			$state = 1;
		} else {
			$state = 0;
		}
		$options[] = array('value' => 'curl', 'descr' => 'cURL',
			'state' => $state);

		if (Config::get('apiConnectionMethod') == 'file') {
			$state = 1;
		} else {
			$state = 0;
		}
		$options[] = array('value' => 'file', 'descr' => 'file',
			'state' => $state);

		return $options;
	}

	public static function createSelectStats()
	{
		$options = array();
		if (Config::get('public_stats') == 'none') {
			$state = 1;
		} else {
			$state = 0;
		}
		$options[] = array('value' => 'do nothing', 'descr' => 'do nothing',
			'state' => $state);

		if (Config::get('public_stats') == 'remove') {
			$state = 1;
		} else {
			$state = 0;
		}
		$options[] = array('value' => 'remove', 'descr' => 'remove',
			'state' => $state);

		return $options;
	}

	public static function createPilot()
	{
		$numeric = false;
		$qry = Database\Factory::getDBQuery();
		$plt_id = PILOT_ID;
		if (isset($_POST['option_add_pilotid'])
				&& $_POST['option_add_pilotid']) {
			$_POST['option_add_pilotid'] = preg_replace("/[^0-9a-zA-Z-_.' ]/",
					'', $_POST['option_add_pilotid']);
			$plt_id = $_POST['option_add_pilotid'];

			if (is_numeric($_POST['option_add_pilotid'])) {
				$numeric = true;
			}
		} else {
			return '<input type="text" id="option_add_pilotid"'
					.' name="option_add_pilotid" value="" size="40"'
					.' maxlength="64" />';
		}

		if (strlen(trim($plt_id == '')) > 0)
			$plt_id = 0;

		if ($numeric && $plt_id > 0) {
			//second condition is for when nothing was posted and it uses the
			//old PILOT_ID
			$plt_id = intval($plt_id);

			if ($plt_id > 90000000) { //external IDs are over 90 million for characters
				$qry->execute("SELECT `plt_name`, `plt_id` FROM `kb3_pilots`"
						." WHERE `plt_externalid` = ".$plt_id);
				if (!$qry->recordCount()) {
					return admin_Config::nameToId('idtoname', 'p', $plt_id);
				}
				$res = $qry->getRow();
				$_POST['option_add_pilotid'] = $plt_id = intval($res['plt_id']);
				$pilots = Config::get('cfg_pilotid');
				if (!in_array($plt_id, $pilots))
					$pilots[] = $plt_id;
				Config::set('cfg_pilotid', $pilots);

				$html = '<input type="text" id="option_add_pilotid"'
						.' name="option_add_pilotid" value="" size="40"'
						.' maxlength="64" />';
				return $html;
			}
			else { //id not within external range
				$qry->execute("SELECT `plt_name` FROM `kb3_pilots` WHERE"
						." `plt_id` = ".$plt_id);
				$html = '<input type="text" id="option_add_pilotid"'
						.' name="option_add_pilotid" value="" size="40"'
						.' maxlength="64" />';
				if (!$qry->recordCount()) {
					return $html;
				}
				$arr = Config::get('cfg_pilotid');
				if (!in_array($plt_id, $arr)) {
					$arr[] = $plt_id;
				}
				Config::set('cfg_pilotid', $arr);
				unset($_POST['option_add_pilotid']);
				return $html; // . ' &nbsp;('.$res['plt_name'].')';
			}
		} else if (is_string($plt_id) && strlen($plt_id) > 0) { //non-numeric
			$qry->execute("SELECT `plt_id`, `plt_name` FROM `kb3_pilots` WHERE"
					." `plt_name` like '".$qry->escape($plt_id)."'");

			if (!$qry->recordCount()) {//name not found, let's look it up
				return admin_Config::nameToId('nametoid', 'p', $plt_id);
			} else { //name is found
				$res = $qry->getRow();
				$_POST['option_add_pilotid'] = $plt_id = intval($res['plt_id']);
				$pilots = Config::get('cfg_pilotid');
				if (!in_array($plt_id, $pilots))
					$pilots[] = $plt_id;
				Config::set('cfg_pilotid', $pilots);
				$html = '<input type="text" id="option_add_pilotid"'
						.' name="option_add_pilotid" value="" size="40"'
						.' maxlength="64" />';
				return $html;
			}
		}
		else { //sometimes this may happen
			$html = '<input type="text" id="option_add_pilotid"'
					.' name="option_add_pilotid" value="" size="40"'
					.' maxlength="64" />';
			return $html;
		}
	}

	public static function createCorp()
	{
		$qry = Database\Factory::getDBQuery();
		$numeric = false;
		$crp_id = 0;

		if (isset($_POST['option_add_corpid']) && $_POST['option_add_corpid']) {
			$_POST['option_add_corpid'] = preg_replace("/[^0-9a-zA-Z-_.' ]/",
					'', $_POST['option_add_corpid']);
			$crp_id = $_POST['option_add_corpid'];

			if (is_numeric($_POST['option_add_corpid'])) {
				$numeric = true;
			}
		} else {
			return '<input type="text" id="option_add_corpid"'
				.' name="option_add_corpid" value="" size="40"'
				.' maxlength="64" />';
		}

		if (strlen(trim($crp_id == '')) > 0) {
			$crp_id = 0;
		}

		if ($numeric && $crp_id > 0) {
			//second condition is for when nothing was posted and it uses the
			//old PILOT_ID
			$crp_id = intval($crp_id);

			if ($crp_id > 98000000) { //external IDs are over 98 million for corps
				$qry->execute("SELECT `crp_name`, `crp_id` FROM `kb3_corps`"
						." WHERE `crp_external_id` = ".$crp_id);
				if (!$qry->recordCount()) {
					return admin_Config::nameToId('idtoname', 'c', $crp_id);
				}
				$res = $qry->getRow();
				$_POST['option_add_corpid'] = $crp_id = intval($res['crp_id']);
				$arr = Config::get('cfg_corpid');
				if (!in_array($crp_id, $arr))
					$arr[] = $crp_id;
				Config::set('cfg_corpid', $arr);

				$html = '<input type="text" id="option_add_corpid"'
						.' name="option_add_corpid" value="" size="40"'
						.' maxlength="64" />';
				return $html;
			}
			else { //id not within external range
				$qry->execute("SELECT `crp_name` FROM `kb3_corps` WHERE"
						." `crp_id` = ".$crp_id);
				$html = '<input type="text" id="option_add_corpid"'
						.' name="option_add_corpid" value="" size="40"'
						.' maxlength="64" />';
				if (!$qry->recordCount()) {
					return $html;
				}
				$arr = Config::get('cfg_corpid');
				if (!in_array($crp_id, $arr)) {
					$arr[] = $crp_id;
				}
				Config::set('cfg_corpid', $arr);
				unset($_POST['option_add_corpid']);
				return $html;
			}
		} else if (is_string($crp_id) && strlen($crp_id) > 0) { //non-numeric
			$qry->execute("SELECT `crp_id`, `crp_name` FROM `kb3_corps` WHERE"
					." `crp_name` like '".$qry->escape($crp_id)."'");

			if (!$qry->recordCount()) {//name not found, let's look it up
				return admin_Config::nameToId('nametoid', 'c', $crp_id);
			} else { //name is found
				$res = $qry->getRow();
				$_POST['option_add_corpid'] = $crp_id = intval($res['crp_id']);
				$arr = Config::get('cfg_corpid');
				if (!in_array($crp_id, $arr)) {
					$arr[] = $crp_id;
				}
				Config::set('cfg_corpid', $arr);
				$html = '<input type="text" id="option_add_corpid"'
						.' name="option_add_corpid" value="" size="40"'
						.' maxlength="64" />';
				return $html;
			}
		} else { //sometimes this may happen
			$html = '<input type="text" id="option_add_corpid"'
					.' name="option_add_corpid" value="" size="40"'
					.' maxlength="64" />';
			return $html;
		}
	}

	public static function createAlliance()
	{
		$qry = Database\Factory::getDBQuery();
		$numeric = false;
		$all_id = 0;

		if (isset($_POST['option_add_allianceid'])
				&& $_POST['option_add_allianceid']) {
			$_POST['option_add_allianceid'] = preg_replace(
					"/[^0-9a-zA-Z-_.' ]/", '', $_POST['option_add_allianceid']);
			$all_id = $_POST['option_add_allianceid'];

			if (is_numeric($_POST['option_add_allianceid'])) {
				$numeric = true;
			}
			unset($_POST['option_add_allianceid']);
		} else {
			unset($_POST['option_add_allianceid']);
			return '<input type="text" id="option_add_allianceid"'
					.' name="option_add_allianceid" value="" size="40"'
					.' maxlength="64" />';
		}

		if (strlen(trim($all_id == '')) > 0) {
			$all_id = 0;
		}

		if ($numeric && $all_id > 0) {
			$all_id = intval($all_id);
			if ($all_id > 99000000) { //external IDs are over 99 million for alliances
				$qry->execute("SELECT `all_name`, `all_id` FROM `kb3_alliances`"
						." WHERE `all_external_id` = ".$all_id);
				if (!$qry->recordCount()) {
					return admin_Config::nameToId('idtoname', 'a', $all_id);
				}
				$res = $qry->getRow();
				$all_id = $res['all_id'];
				$arr = Config::get('cfg_allianceid');
				if (!in_array($all_id, $arr)) {
					$arr[] = $all_id;
				}
				Config::set('cfg_allianceid', $arr);
				$html = '<input type="text" id="option_add_allianceid"'
						.' name="option_add_allianceid" value="" size="40"'
						.' maxlength="64" />';
				return $html;
			} else { //id not within external range
				$qry->execute("SELECT `all_name` FROM `kb3_alliances`"
						." WHERE `all_id` = ".$all_id);
				$html = '<input type="text" id="option_add_allianceid"'
						.' name="option_add_allianceid" value="" size="40"'
						.' maxlength="64" />';
				if (!$qry->recordCount()) {
					return $html;
				}
				$res = $qry->getRow();
				$arr = Config::get('cfg_allianceid');
				if (!in_array($all_id, $arr)) {
					$arr[] = $all_id;
				}
				Config::set('cfg_allianceid', $arr);
				unset($_POST['option_add_allianceid']);
				return $html;
			}
		} else if (is_string($all_id) && strlen($all_id) > 0) { //non-numeric
			$qry->execute("SELECT `all_id`, `all_name` FROM `kb3_alliances`"
					." WHERE `all_name` like '".$qry->escape($all_id)."'");

			if (!$qry->recordCount()) {//name not found, let's look it up
				return admin_Config::nameToId('nametoid', 'a', $all_id);
			} else { //name is found
				$res = $qry->getRow();
				$_POST['option_add_allianceid'] = $all_id = $res['all_id'];
				$arr = Config::get('cfg_allianceid');
				if (!in_array($all_id, $arr)) {
					$arr[] = $all_id;
				}
				Config::set('cfg_allianceid', $arr);
				$html = '<input type="text" id="option_add_allianceid"'
						.' name="option_add_allianceid" value="" size="40"'
						.' maxlength="64" />';
				return $html;
			}
		} else { //sometimes this may happen
			$html = '<input type="text" id="option_add_allianceid"'
					.' name="option_add_allianceid" value="" size="40"'
					.' maxlength="64" />';
			return $html;
		}
	}

	/**
	 * Remove selected pilots then return an array of pilots remaining.
	 */
	public static function removePilot()
	{
		if (isset($_POST['option_rem_pilotid'])
				&& $_POST['option_rem_pilotid']) {
			$arr = Config::get('cfg_pilotid');
			$key = array_search(intval($_POST['option_rem_pilotid']), $arr);
			if ($key !== false) {
				unset($arr[$key]);
			}
			sort($arr);
			Config::set('cfg_pilotid', $arr);
			unset($_POST['option_rem_pilotid']);
		}

		$options = array();
		foreach (Config::get('cfg_pilotid') as $val) {
			$plt = new Pilot($val);
			$options[$plt->getName()] = array('value' => $val,
				'descr' => $plt->getName(), 'state' => 0);
		}
		ksort($options);
		array_unshift($options, array('value' => '0', 'descr' => '-',
			'state' => 1));
		return $options;
	}

	/**
	 * Remove selected corps then return an array of corps remaining.
	 */
	public static function removeCorp()
	{
		if (isset($_POST['option_rem_corpid']) && $_POST['option_rem_corpid']) {
			$arr = Config::get('cfg_corpid');
			$key = array_search(intval($_POST['option_rem_corpid']), $arr);
			if ($key !== false) {
				unset($arr[$key]);
			}
			sort($arr);
			Config::set('cfg_corpid', $arr);
			unset($_POST['option_rem_corpid']);
		}

		$options = array();
		foreach (Config::get('cfg_corpid') as $val) {
			$crp = new Corporation($val);
			$options[$crp->getName()] = array('value' => $val,
				'descr' => $crp->getName(), 'state' => 0);
		}
		ksort($options);
		array_unshift($options, array('value' => '0', 'descr' => '-',
			'state' => 1));
		return $options;
	}

	/**
	 * Remove selected alliances then return an array of alliances remaining.
	 */
	public static function removeAlliance()
	{
		if (isset($_POST['option_rem_allianceid'])
				&& $_POST['option_rem_allianceid']) {
			$arr = Config::get('cfg_allianceid');
			$key = array_search(intval($_POST['option_rem_allianceid']), $arr);
			if ($key !== false) {
				unset($arr[$key]);
			}
			sort($arr);
			Config::set('cfg_allianceid', $arr);
			unset($_POST['option_rem_allianceid']);
		}

		$options = array();
		foreach (Config::get('cfg_allianceid') as $val) {
			$all = new Alliance($val);
			$options[$all->getName()] = array('value' => $val,
				'descr' => $all->getName(), 'state' => 0);
		}
		ksort($options);
		array_unshift($options, array('value' => '0', 'descr' => '-',
			'state' => 1));
		return $options;
	}

	public static function reload()
	{
		header("Location: http://".$_SERVER['HTTP_HOST']
				.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
	}

	public static function nameToId($type, $set, $value)
	{
		if ($type == 'nametoid') {
			$api = new \EDK\EVEAPI\NametoID();
			$api->setNames($value);
		} else if ($type == 'idtoname') {
			$api = new \EDK\EVEAPI\IDtoName();
			$api->setIDs($value);
		}
		$api->fetchXML();

		if ($type == 'nametoid') {
			$char_info = $api->getNameData();
		} else if ($type == 'idtoname') {
			$char_info = $api->getIDData();
		}

		if (isset($char_info[0]['characterID'])
				&& strlen($char_info[0]['characterID']) > 0) {
			$timestamp = gmdate('Y.m.d H:i:s', time());

			if ($set == 'p') {
				$all = Alliance::add('Unknown');

				$crp = Corporation::add('Unknown', $all, $timestamp, 0, false);

				$plt = Pilot::add($char_info[0]['name'], $crp, $timestamp,
						$char_info[0]['characterID'], false);

				$_POST['option_cfg_pilotid'] = $value = $plt->getID();
				$pilots = Config::get('cfg_pilotid');
				$pilots[] = intval($value);
				Config::set('cfg_pilotid', $pilots);

				$html = '<input type="text" id="option_cfg_pilotid"'
						.' name="option_cfg_pilotid" value="" size="40"'
						.' maxlength="64" />';
			} else if ($set == 'c') {
				$all = Alliance::add('Unknown');

				$crp = Corporation::add($char_info[0]['name'], $all, $timestamp,
						$char_info[0]['characterID'], false);

				$_POST['option_cfg_corpid'] = $value = $crp->getID();
				$corps = Config::get('cfg_corpid');
				$corps[] = intval($value);
				Config::set('cfg_corpid', $corps);

				$html = '<input type="text" id="option_cfg_corpid"'
						.' name="option_cfg_corpid" value="" size="40"'
						.' maxlength="64" />';
			} else if ($set == 'a') {
				$all = Alliance::add($char_info[0]['name'], $char_info[0]['characterID']);

				$_POST['option_cfg_allianceid'] = $value = $all->getID();
				$alliances = Config::get('cfg_allianceid');
				$alliances[] = intval($value);
				Config::set('cfg_allianceid', $alliances);

				$html = '<input type="text" id="option_cfg_allianceid"'
						.' name="option_cfg_allianceid" value="" size="40"'
						.' maxlength="64" />';
			}
			return $html;
		} else {
			return $html;
		}
	}

}

class update
{

	private static $codeVersion;
	private static $dbVersion;

	/** Check if board is at latest update
	 *
	 * @return string HTML link to update or show that no update is needed.
	 */
	public static function codeCheck()
	{
		if (!class_exists('DOMDocument')) {
			return "The required PHP DOMDocument libraries are not installed.";
		}
		update::checkStatus();
		/*
		if (isNewerVersion(update::$codeVersion, KB_VERSION)) {
			return "<div>Code updates are available, <a href='"
					.URI::page('admin_upgrade')."'>here</a></div><br/>";
		}
		return "<div>No updates available</div>";*/
	}

	/**
	 * Check if database is at latest update
	 *
	 * @return string HTML link to update or show that no update is needed.
	 */
	public static function dbCheck()
	{
		if (!class_exists('DOMDocument')) {
			return "The required PHP DOMDocument libraries are not installed.";
		}
		update::checkStatus();
		if (update::$dbVersion > Config::get('upd_dbVersion')) {
			return "<div>Database updates are available, <a href='"
					.URI::page('admin_upgrade')."'>here</a></div><br/>";
		}
		return "<div>No updates available</div>";
	}

	/**
	 * Updates status xml if necessary.
	 */
	public static function checkStatus()
	{
		$xml = new \EDK\CCPDB\UpdateXMLParser();
		if ($xml->getXML() < 3) {
			$xml->retrieveData();
			update::$codeVersion = $xml->getLatestCodeVersion();
			update::$dbVersion = $xml->getLatestDBVersion();
		}
		return;
	}

}
