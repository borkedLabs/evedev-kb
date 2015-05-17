<?php

class ValueCommand extends CronCommand
{
	public function execute()
	{
		$url = config::get('itemPriceCrestUrl');
		if ($url == null || $url == "")
			$url = ValueFetcherCrest::$CREST_URL;

		$fetch = new ValueFetcherCrest($url);

		// Fetch
		$count = $fetch->fetchValues();

		// Echo result
		echo $count." Items updated\n";
	}
}