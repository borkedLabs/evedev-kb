<?php

namespace EDK\Command;

use EDK\Core\Config;
use EDK\CREST\ValueFetcher;

class Value extends Command
{
	public function execute()
	{
		$url = Config::get('itemPriceCrestUrl');
		if ($url == null || $url == "")
		{
			$url = ValueFetcher::$CREST_URL;
		}

		$fetch = new ValueFetcher($url);

		// Fetch
		$count = $fetch->fetchValues();

		// Echo result
		println($count." Items updated");
	}
}