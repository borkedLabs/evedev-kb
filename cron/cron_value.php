<?php

class ValueCommand extends CronCommand
{
	public function execute()
	{
		println("Starting CREST item value update");

		$fetch = new ValueFetcherEsi();
		// Fetch
		$count = $fetch->fetchValues();

		// Echo result
		println($count." Items updated");
	}
}