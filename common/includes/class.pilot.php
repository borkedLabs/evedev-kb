<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

/**
 * Creates a new Pilot or fetches an existing one from the database.
 * @package EDK
 */
class Pilot extends Entity
{
	private $executed = false;
	private $id = 0;
	private $externalid = 0;
	private $corpid = null;
	private $valid = false;
	private $corp = null;
	private $name = null;
	private $updated = null;

	/**
	 * Create a new Pilot object from the given ID.
	 *
	 * @param integer $id The pilot ID.
	 * @param integer $externalID The external pilot ID.
	 * @param string $name The pilot name.
	 * @param integer|Corporation The pilot's corporation.
	 */
	function __construct($id = 0, $externalID = 0, $name = null, $corp = null)
	{
		$this->id = intval($id);
		$this->externalid = intval($externalID);
		if (isset($name)) {
			$this->name = $name;
		}
		if (isset($corp)) {
			if (is_numeric($corp)) {
				$this->corpid = $corp;
			} else {
				$this->corp = $corp;
				$this->corpid = $corp->getID();
			}
		}
	}

	/**
	 * Return the pilot ID.
	 *
	 * @return integer
	 */
	function getID()
	{
		if ($this->id) {
			return $this->id;
		} else if ($this->externalid) {
			$this->execQuery();
			return $this->id;
		} else {
			return 0;
		}
	}

	/**
	 * Return the pilot's CCP ID.
	 * When populateList is true, the lookup will return 0 in favour of getting the
	 *  external ID from CCP. This helps the kill_detail page load times.
	 *
	 * @param boolean $populateList
	 * @return integer
	 */
	public function getExternalID($populateList = false)
	{
                // sanity-check: don't return external IDs that clearly aren't characterIDs (but might be typeIDs)
                if(is_numeric($this->externalid) && $this->externalid > 0 && $this->externalid < 90000000)
                {
                    return 0;
                }
		if ($this->externalid) {
			return $this->externalid;
		}
		if (!$populateList) {
			$this->execQuery();
                        // sanity-check: don't return external IDs that clearly aren't characterIDs (but might be typeIDs)
                        if(is_numeric($this->externalid) && $this->externalid < 90000000)
                        {
                            return 0;
                        }
			if ($this->externalid) {
				return $this->externalid;
			}

			$pqry = new DBPreparedQuery();
			$sql = "SELECT typeID FROM kb3_invtypes, kb3_pilots WHERE typeName = plt_name AND plt_id = ?";
			$id = "";
			$pqry->prepare($sql);
			$pqry->bind_param('i', $this->id);
			$pqry->bind_result($id);
			if ($pqry->execute()) {
				if ($pqry->recordCount()) {
					$pqry->fetch();
					$this->setCharacterID($id);
					return $this->externalid;
				}
			}
			// If getName() != name_ then this is a structure, not a pilot.
			if ($this->getName() != $this->name) {
				return 0;
			}
			$myID = new API_NametoID();
			$myID->setNames($this->getName());
			$myID->fetchXML();
			$myNames = $myID->getNameData();

			if ($this->setCharacterID($myNames[0]['characterID'])) {
				return $this->externalid;
			} else {
				return 0;
			}
		}
		else return 0;
	}

	/**
	 * Return the pilot name.
	 *
	 * @return string
	 */
	public function getName()
	{
		if (!$this->name) {
			$this->execQuery();
		}
		$pos = strpos($this->name, "#");
		if ($pos === false) {
			// Hacky, hacky, hack hack
			// TODO: Fix this and change old kills to suit.
			$pos = strpos($this->name, "- ");
			if ($pos === false) return $this->name;
			else if (strpos($this->name, "Moon") == false)
					return substr($this->name, $pos + 2);
			else return $this->name;
		} else {
			$name = explode("#", $this->name);
			return $name[3];
		}
	}

	/**
	 * Return the URL for the pilot's portrait.
	 *
	 * @param integer $size The desired portrait size.
	 * @return string URL for a portrait.
	 */
	public function getPortraitURL($size = 64)
	{
		if (!$this->externalid) {
			$this->execQuery();
		}
		if (!$this->externalid) {
			return KB_HOST."/thumb.php?type=pilot&amp;id=".$this->id."&amp;size=$size&amp;int=1";
		} else {
			return imageURL::getURL('Pilot', $this->externalid, $size);
		}
	}

	/**
	 * Return a URL for the details page of this Pilot.
	 *
	 * @return string The URL for this Pilot's details page.
	 */
	function getDetailsURL()
	{
		if ($this->getExternalID()) {
			return edkURI::page('pilot_detail', $this->externalid,
					'plt_ext_id');
		} else {
			return edkURI::page('pilot_detail', $this->id, 'plt_id');
		}
	}

	/**
	 * Return the file path for the pilot's portrait.
	 *
	 * The portrait is not generated by this function. If the portrait does
	 * not exist then the path it would use is returned.
	 * @param integer $size The desired portrait size.
	 * @param integer $id The pilot ID to use. If not given and this is instantiated
	 * use the ID for this pilot.
	 * @return string path for a portrait.
	 */
	public function getPortraitPath($size = 64, $id = 0)
	{
		$size = intval($size);
		$id = intval($id);
		if (!$id) {
			$id = $this->getExternalID();
		}
		return CacheHandler::getInternal($id."_".$size.".jpg", "img");
	}

	/**
	 * Fetch the pilot details from the database using the id given on construction.
	 */
	private function execQuery()
	{
		if (!$this->executed) {
			if (!$this->externalid && !$this->id) {
				$this->valid = false;
				return;
			}
			if ($this->id && $this->isCached()) {
				$cache = $this->getCache();
				$this->valid = $cache->valid;
				$this->id = $cache->id;
				$this->name = $cache->name;
				$this->corpid = $cache->corpid;
				$this->externalid = $cache->externalid;

				$this->executed = true;
				return;
			}
			$qry = DBFactory::getDBQuery();
			$sql = 'SELECT * FROM kb3_pilots plt'
					.' LEFT JOIN kb3_corps crp ON plt_crp_id = crp_id'
					.' LEFT JOIN kb3_alliances ali ON crp_all_id = all_id'
					." WHERE";
			if ($this->id) {
				$sql .= ' plt.plt_id = '.$this->id;
			} else {
				$sql .= ' plt.plt_externalid = '.$this->externalid;
			}
			$qry->execute($sql) or die($qry->getErrorMsg());
			if ($this->externalid && !$qry->recordCount()) {
				$this->fetchPilot();
				if ($this->id) {
					$this->valid = true;
				}
			} else if (!$qry->recordCount()) {
				$this->valid = false;
			} else {
				$row = $qry->getRow();
				$this->valid = true;
				$this->id = (int) $row['plt_id'];
				$this->name = $row['plt_name'];
				$this->corpid = (int) $row['plt_crp_id'];
				$this->externalid = (int) $row['plt_externalid'];
				$this->putCache();
			}
			$this->executed = true;
		}
	}

	/**
	 * Return the most recently recorded Corporation this pilot is a member of.
	 *
	 * @return Corporation Corporation object
	 */
	public function getCorp()
	{
		if (isset($this->corp)) {
			return $this->corp;
		}
		if (!isset($this->corpid)) {
			$this->execQuery();
		}

		$this->corp = Cacheable::factory('Corporation', $this->corpid);
		return $this->corp;
	}

	/**
	 * Check if the id given on construction is valid.
	 *
	 * @return boolean true if this pilot exists.
	 */
	public function exists()
	{
		$this->execQuery();
		return $this->valid;
	}

	/**
	 * Add a new pilot to the database or update the details of an existing one.
	 *
	 * @param string $name Pilot name
	 * @param Corporation $corp Corporation object for this pilot's corporation
	 * @param string $timestamp time this pilot's corp was updated
	 * @param integer $externalID CCP external id
	 * @param boolean $loadExternals Whether to check for an external ID
	 * @return Pilot
	 */
	public static function add($name, $corp, $timestamp, $externalID = 0,
			$loadExternals = true)
	{
		if (!$name) {
			trigger_error("Attempt to add a pilot with no name. Aborting.", E_USER_ERROR);
			// If things are going this wrong, it's safer to die and prevent more harm
			die;
		} else if (!$corp->getID()) {
			trigger_error("Attempt to add a pilot with no corp. Aborting.", E_USER_ERROR);
			// If things are going this wrong, it's safer to die and prevent more harm
			die;
		}
		// Check if pilot exists with a non-cached query.
		$qry = DBFactory::getDBQuery(true);
		$name = stripslashes($name);
		// Insert or update a pilot with a cached query to update cache.
		$qryI = DBFactory::getDBQuery(true);
		$qry->execute("SELECT * FROM kb3_pilots WHERE plt_name = '".$qry->escape($name)."'");

		if (!$qry->recordCount()) {
			$externalID = (int)$externalID;
			// If no external id is given then look it up.
			if (!$externalID && $loadExternals) {
				$myID = new API_NametoID();
				$myID->setNames($name);
				$myID->fetchXML();
				$myNames = $myID->getNameData();
				$externalID = (int)$myNames[0]['characterID'];
			}
			// If we have an external id then check it isn't already in use.
			// If we find it then update the old corp with the new name and
			// return.
			if ($externalID) {
				$qry->execute("SELECT * FROM kb3_pilots WHERE plt_externalid = "
						.$externalID);
				if ($qry->recordCount()) {
					$row = $qry->getRow();
					$pilot = Pilot::getByID($row['plt_id']);
					$qryI->execute("UPDATE kb3_pilots SET plt_name = '".$qry->escape($name)
							."' WHERE plt_externalid = ".$externalID);
					if ($qryI->affectedRows() > 0) {
						Cacheable::delCache($pilot);
					}
					$qryI->execute("UPDATE kb3_pilots SET plt_crp_id = "
							.$corp->getID().", plt_updated = "
							."date_format( '".$timestamp
							."', '%Y.%m.%d %H:%i:%s') WHERE plt_externalid = "
							.$externalID." AND plt_crp_id <> ".$corp->getID()
							." AND ( plt_updated < date_format( '".$timestamp
							."', '%Y-%m-%d %H:%i') OR plt_updated is null )");
					if ($qryI->affectedRows() > 0) {
						Cacheable::delCache($pilot);
					}
					return $pilot;
				}
			}
			$qry->execute("INSERT INTO kb3_pilots (plt_name, plt_crp_id, "
					."plt_externalid, plt_updated) values ('".$qry->escape($name)."', "
					.$corp->getID().",	".$externalID.",
					date_format( '".$timestamp."', '%Y.%m.%d %H:%i:%s'))
					ON DUPLICATE KEY UPDATE plt_crp_id=".$corp->getID().",
					plt_externalid=".$externalID.",
					plt_updated=date_format( '".$timestamp."', '%Y.%m.%d %H:%i:%s')");
			return new Pilot($qry->getInsertID(), $externalID, $name,
					$corp->getID());
		} else {
			// Name found.
			$row = $qry->getRow();
			$id = $row['plt_id'];
			if (!is_null($row['plt_updated'])) {
				$updated = strtotime($row['plt_updated']." UTC");
			} else {
				$updated = 0;
			}
			if ($updated < strtotime($timestamp." UTC")
					&& $corp->getID() != $row['plt_crp_id']) {
				$qryI->execute("UPDATE kb3_pilots SET plt_crp_id = "
						.$corp->getID().", plt_updated = '".$timestamp
						."' WHERE plt_name = '".$qry->escape($name)."'"
						." AND plt_crp_id <> ".$corp->getID()
						." AND ( plt_updated < '".$timestamp
						."' OR plt_updated is null )");
			}
			$plt = new Pilot($id, $externalID, $name, $corp);
			if (!$row['plt_externalid'] && $externalID) {
				$plt->executed = true;
				$plt->setCharacterID($externalID);
			}
			return $plt;
		}
	}

	/**
	 * Return whether this pilot was updated before the given timestamp.
	 *
	 * @param string $timestamp A timestamp to compare this pilot's details with.
	 * @return boolean - true if update time was before the given timestamp.
	 */
	public function isUpdatable($timestamp)
	{
		$timestamp = preg_replace("/\./", "-", $timestamp);
		if (isset($this->updated)) {
			if (is_null($this->updated)
					|| strtotime($timestamp." UTC") > $this->updated) {
				return true;
			} else {
				return false;
			}
		}
		$qry = DBFactory::getDBQuery();
		$qry->execute("select plt_id
                        from kb3_pilots
                       where plt_id = ".$this->id."
                         and ( plt_updated < date_format( '".$timestamp."', '%Y-%m-%d %H:%i')
                               or plt_updated is null )");

		return $qry->recordCount() == 1;
	}

	/**
	 * Set the CCP external ID for this pilot.
	 *
	 * If a character already exists with this id then a name change is assumed
	 * and the old pilot is updated.
	 * @param integer $externalID CCP external ID for this pilot.
	 */
	public function setCharacterID($externalID)
	{
		$externalID = (int) $externalID;
		if (!$externalID) {
			return false;
		}
		$this->execQuery();
		if (!$this->id) {
			return false;
		} else if ($externalID == $this->externalid  && $externalID > 90000000) {
			return true;
		}
		

		$qry = DBFactory::getDBQuery(true);
		$qry->execute("SELECT plt_id FROM kb3_pilots WHERE plt_externalid = "
				.$externalID." AND plt_id <> ".$this->id);
		if ($qry->recordCount()) {
			$result = $qry->getRow();
			$qry->autocommit(false);
			$old_id = $result['plt_id'];
			$qry->execute("UPDATE kb3_kills SET kll_victim_id = ".$old_id
					." WHERE kll_victim_id = ".$this->id);
			$qry->execute("UPDATE kb3_kills SET kll_fb_plt_id = ".$old_id
					." WHERE kll_fb_plt_id = ".$this->id);
			$qry->execute("UPDATE kb3_inv_detail SET ind_plt_id = ".$old_id
					." WHERE ind_plt_id = ".$this->id);
			$qry->execute("DELETE FROM kb3_sum_pilot WHERE psm_plt_id = "
					.$this->id);
			$qry->execute("DELETE FROM kb3_sum_pilot WHERE psm_plt_id = "
					.$old_id);
			$qry->execute("DELETE FROM kb3_pilots WHERE plt_id = ".$this->id);
			$qry->execute("UPDATE kb3_pilots SET plt_name = '"
					.$qry->escape($this->name)."' where plt_id = ".$old_id);
			$this->id = $old_id;
			$qry->autocommit(true);
		} else {
			$qry->execute("UPDATE kb3_pilots SET plt_externalid = "
					.$externalID." WHERE plt_id = ".$this->id);
		}
                
                if($externalID < 90000000)
                {
                    return false;
                }
                    
                $this->externalid = $externalID;
		Cacheable::delCache($this);
		$this->valid = true;
		return true;
	}

	/**
	 * Lookup a pilot name and return a Pilot object.
	 *
	 * @param string $name The pilot name to look up.
	 * @return Pilot|boolean returns false if the Pilot was not found.
	 */
	public static function lookup($name)
	{
		$qry = DBFactory::getDBQuery();
		$qry->execute("SELECT plt_id, plt_externalid, plt_crp_id, plt_updated "
				."FROM kb3_pilots WHERE plt_name = '"
				.$qry->escape(stripslashes($name))."'");
		if ($qry->recordCount()) {
			$row = $qry->getRow();
			return new Pilot($row['plt_id'], $row['plt_externalid'],
					$row['plt_name'], $row['plt_crp_id']);
		} else {
			return false;
		}
	}

	/**
	 * Fetch the pilot name from CCP using the stored external ID.
	 *
	 * Corporation will be set to Unknown.
	 */
	private function fetchPilot()
	{
		if (!$this->externalid) {
			return false;
		}
			$apiInfo = new API_CharacterInfo();
			$apiInfo->setID($this->externalid);
			$result = $apiInfo->fetchXML();

			if($result == "") {
				$data = $apiInfo->getData();
				if(isset($data['alliance']) && isset($data['allianceID']))
                                {
                                    $this->alliance = Alliance::add($data['alliance'], $data['allianceID']);
                                }
                                else {
                                    $this->alliance = Alliance::add('None');
                                }
                                
				$this->corp = Corporation::add($data['corporation'],
					$this->alliance, $apiInfo->getCurrentTime(),
					$data['corporationID']);
				$this->name = $data['characterName'];
				$Pilot = Pilot::add($data['characterName'], $this->corp, $apiInfo->getCurrentTime(), $data['characterID']);
                                $this->id = $Pilot->getID();
			} else {
				return false;
			}
	}

	/**
	 * Return a new object by ID. Will fetch from cache if enabled.
	 *
	 * @param mixed $id ID to fetch $id
	 * @return Pilot
	 */
	static function getByID($id)
	{
		return Cacheable::factory(get_class(), $id);
	}
}
