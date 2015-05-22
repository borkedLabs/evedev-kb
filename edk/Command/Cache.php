<?php

namespace EDK\Command;

use EDK\Core\Config;

class Cache extends Command
{
	public function execute()
	{
		$config = new Config(KB_SITE);

		println("Running Cron_Cache on " . gmdate("M d Y H:i"));
		println("");

		// Alliance
		$myAlliAPI = new \EDK\EVEAPI\Alliance();
		
		println("Caching Alliance XML");
		$Allitemp .= $myAlliAPI->fetchalliances();
	}
}