<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

 use EDK\StatSummary\AllianceSummary;
 use EDK\StatSummary\CorpSummary;
 use EDK\StatSummary\PilotSummary;

/**
 * Convenience class to call summary caches for alliance, corp and pilots.
 * @package EDK
 */
class summaryCache
{
	static public function addKill($kill)
	{
		AllianceSummary::addKill($kill);
		CorpSummary::addKill($kill);
		PilotSummary::addKill($kill);
	}
	
	static public function delKill($kill)
	{
		AllianceSummary::delKill($kill);
		CorpSummary::delKill($kill);
		PilotSummary::delKill($kill);
	}
	
	static public function update($kill, $difference)
	{
		AllianceSummary::update($kill, $difference);
		CorpSummary::update($kill, $difference);
		PilotSummary::update($kill, $difference);
	}
}