<?php

class CacheCommand extends CronCommand
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		$outhead = "Running Cron_Cache on " . gmdate("M d Y H:i") . "\n\n";
		$html = '';

		// Alliance
		$myAlliAPI = new API_Alliance();
		$Allitemp .= $myAlliAPI->fetchalliances();
		$html .= "Caching Alliance XML \n";

		if ($html)
		{
			$html = str_replace("<div class=block-header2>","",$html);
			$html = str_replace("</div>","\n",$html);
			$html = str_replace("<br>","\n",$html);
		 
			//print $outhead . strip_tags($out, '<a>');
			print $outhead . strip_tags($html);
		}
	}
}