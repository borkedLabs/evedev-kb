<?php

namespace EDK\Command;

use EDK\Core\Config;

class Value extends Command
{
	public function execute()
	{
		$url = Config::get('itemPriceCrestUrl');
		if ($url == null || $url == "")
		{
			$url = \ValueFetcherCrest::$CREST_URL;
		}

		$fetch = new \ValueFetcherCrest($url);

		// Fetch
		$count = $fetch->fetchValues();

		// Echo result
		println($count." Items updated");
	}
}