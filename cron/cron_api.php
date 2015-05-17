<?php

class ApiCommand extends CronCommand
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		define('KB_TITLE', config::get('cfg_kbtitle'));

		if (!$dir = config::get('cache_dir'))
		{
			$dir = 'cache/data';
		}
		if(!defined('KB_CACHEDIR'))
		{
			define('KB_CACHEDIR', $dir);
		}

		$outhead = "Running API Import on " . gmdate("M d Y H:i") . "\n\n";
		$html = '';
		$outtemp = '';

		$myEveAPI = new API_KillLog();
		$myEveAPI->iscronjob_ = true;

		$qry = new DBQuery();
		$qry->execute("SELECT * FROM kb3_api_keys WHERE key_kbsite = '" . KB_SITE . "' ORDER BY key_name");
		while ($row = $qry->getRow()) {
			if(isset($_GET['feed']) && $_GET['feed'] && $row['key_id'] != $_GET['feed']) {
				continue;
			}
			$html .= "Importing Mails for " . $row['key_name'] . "<br />";
			$html .= $myEveAPI->Import($row['key_name'], $row['key_id'], $row['key_key'], $row['key_flags']);
		}

		$html .= "Time taken = ".(microtime(true) - $cronStartTime)." seconds.";

		$html = $outhead.$html;
		if (php_sapi_name() == 'cli') {
			$html = str_replace("</div>","</div>\n",$html);
			$html = str_replace("<br>","\n",$html);
			$html = str_replace("<br />","\n",$html);
			$html = strip_tags($html);
		}

		echo $html."\n";
	}
}