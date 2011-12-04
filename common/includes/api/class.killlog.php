<?php
/**
 * $Date: 2010-05-30 13:44:06 +1000 (Sun, 30 May 2010) $
 * $Revision: 721 $
 * $HeadURL: https://evedev-kb.googlecode.com/svn/trunk/common/includes/class.eveapi.php $
 * @package EDK
 */

/**
 * API KillLog - /corp/Killlog.xml.aspx
 */
class API_KillLog extends API
{
    function Import($name, $id, $key, $flags)
	{
		$output = "";

		// Skip bad keys
		if ( $flags & KB_APIKEY_BADAUTH || $flags & KB_APIKEY_EXPIRED ) {
			return; // skip bad keys
		}
		
		// also skip legacy keys now
		if( $flags & KB_APIKEY_LEGACY)
			return;

		// reduces strain on DB
		if(function_exists("set_time_limit"))
      		set_time_limit(0);

		$lastdatakillid = 1;
		$currentdatakillid = 0;

		$logsource = "New XML";
		// Load new XML
		$output = "<i>Downloading latest XML file for $name</i><br><br>";
		
		$accts = new API_Account();
		$characters = $accts->fetch($id, $key);
		$posted = array();
		$skipped = array();

		foreach($characters as $char) {
			$output .= "Processing " . $char['charID'] . "<br><br>";
			$currentkill = 0;
			$lastkill = -1;
			while ($lastkill != $currentkill) {
				$lastkill = $currentkill;
				$args = array("characterID" => $char['charID']);
				if($lastkill) {
					$args["beforeKillID"] = $lastkill;
				}
				
				if ( $flags & KB_APIKEY_CORP ) {
					$killLog = self::CallAPI( "corp", "KillLog", $args, $id, $key );
				}
				if ( $flags & KB_APIKEY_CHAR ) {
					$killLog = self::CallAPI( "char", "KillLog", $args, $id, $key );
				}

				if (self::GetError() === null) {
					// Get oldest kill
					$currentkill = 0;
					$sxe = simplexml_load_string($this->pheal->xml);					
					foreach($sxe->result->rowset->row as $row) {
						if($currentkill < (int)$row['killID']) {
							$currentkill = (int)$row['killID'];
						}
					}
				}

				if (self::GetError() !== null) {
					if (self::GetError() == 120 && $this->pheal->xml) {
						// Check if we just need to skip back a few kills
						// i.e. first page of kills is already fetched.
						$pos = strpos($this->pheal->xml, "Expected beforeKillID [");
						if($pos) {
							$pos += 23;
							$pos2 = strpos($this->pheal->xml, "]", $pos);
							$currentkill = (int)substr($this->pheal->xml, $pos, $pos2 - $pos);
						}
					} else if (!$posted && !$skipped) {
						// Something went wrong and no kills were found.
						$qry = DBFactory::getDBQuery();
						$logtype = "Cron Job";

						$qry->execute("insert into kb3_apilog	values( '".KB_SITE."', '"
								.addslashes($name)."',"
								."0, "
								."0, "
								."0, "
								."0, "
								."0, '"
								."Error','"
								."Cron Job','"
								. self::GetError() . "', "
								."UTC_TIMESTAMP() )");						
						return $output;
					} else {
						// We found kills!
						$qry = DBFactory::getDBQuery();
						$logtype = "Cron Job";

						$qry->execute("insert into kb3_apilog values( '".KB_SITE."', '"
								.addslashes($name)."',"
								.count($posted).","
								."0 ,"
								.count($skipped).","
								."0 ,"
								.(count($posted) + count($skipped)).",'"
								."New XML','"
								."Cron Job','"
								. (self::GetError() == 119 ? 0: self::GetError()) . "', "
								."UTC_TIMESTAMP() )");

						return $output;
					}
				}

				$feedfetch = new IDFeed();
				$feedfetch->setXML($this->pheal->xml);
				$feedfetch->setTrust(-1);
				$feedfetch->read();

				$posted = array_merge($posted, $feedfetch->getPosted());
				$skipped = array_merge($skipped, $feedfetch->getSkipped());
 
				$output .= "<div class='block-header2'>"
						.count($posted)." kill".(count($posted) == 1 ? "" : "s")." posted, "
						.count($skipped)." skipped from feed: ".$id.".<br></div>";
				$output .= "<div class='block-header2'>Posted</div>\n";
				foreach($posted as $killid) {
					$output .= "<div><a href='"
							.edkURI::page('kill_detail', $killid[2], 'kll_id')
							."'>Kill ".$killid[0]."</a></div>";
				}
			}
		}			
        return $output;
    }
}
