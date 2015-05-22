<?php
/**
 * $Date: 2010-05-30 13:44:06 +1000 (Sun, 30 May 2010) $
 * $Revision: 721 $
 * $HeadURL: https://evedev-kb.googlecode.com/svn/trunk/common/includes/class.eveapi.php $
 * @package EDK
 */

namespace EDK\EVEAPI;
 
use EDK\Core\Config;
// **********************************************************************************************************************************************
// ****************                                   API Name -> ID Conversion /eve/CharacterID.xml.aspx 	                     ****************
// **********************************************************************************************************************************************
class NametoID extends API
{
	function setNames($names)
	{
		$this->API_Names_ = $names;
	}
	function getNameData()
	{
		return $this->NameData_;
	}

	function clear()
	{
		$this->NameData_ = array();
		unset($this->NameData_);
	}

	public function fetchXML()
	{
		if ($this->API_Names_ == "")
			return "No Names have been input.";

		$data = $this->CallAPI( "eve", "CharacterID", array( "names" => $this->API_Names_ ) , null, null );

		if($data == false) return "Error fetching IDs";

		foreach($data->characters as $character) {
			$this->NameData_[] = array(
					'name'=>strval($character->name),
					'characterID'=>intval($character->characterID));
		}

		return "";
	}
}
