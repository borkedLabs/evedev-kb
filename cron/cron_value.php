<?php

class ValueCommand extends CronCommand
{
	public function execute()
	{
		println("Starting CREST item value update");

		$url = config::get('itemPriceCrestUrl');
		if ($url == null || $url == "")
		{
			$url = CREST_PUBLIC_URL . ValueFetcherCrest::$CREST_PRICES_ENDPOINT;
		}

		$fetch = new ValueFetcherCrest($url);

		// Fetch
		$count = $fetch->fetchValues();

		// Echo result
		println($count." Items updated");
	}
}