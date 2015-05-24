<?php

namespace EDK\Killmail;

class CombinedCollection extends Collection
{
	function CombinedKillList()
	{
	// please only load killlists here
		$this->lists = func_get_args();
		if (!is_array($this->lists))
		{
			trigger_error('No killlists given to CombinedKillList', E_USER_ERROR);
		}
		$this->kills = false;
	}

	public function buildKillArray()
	{
		$this->kills = array();
		foreach ($this->lists as $killlist)
		{
		// reset the list
			$killlist->rewind();

			// load all kills and store them in an array
			while ($kill = $killlist->getKill())
			{
			// take sure that if there are multiple kills all are stored
				if (isset($this->kills[$kill->timestamp_]))
				{
					$this->kills[$kill->timestamp_.rand()] = $kill;
				}
				else
				{
					$this->kills[$kill->timestamp_] = $kill;
				}
			}
		}

		// sort the kills by time
		krsort($this->kills);
	}

	public function getKill()
	{
	// on the first request we load up our kills
		if ($this->kills === false)
		{
			$this->buildKillArray();
			if (is_numeric($this->poffset_) && is_numeric($this->plimit_))
				$this->kills = array_slice($this->kills, $this->poffset_, $this->plimit_);
		}

		// once all kills are out this will return null so we're fine
		return array_shift($this->kills);
	}

	public function rewind()
	{
	// intentionally left empty to overload the standard handle
	}

	public function getCount()
	{
		$count = 0;
		foreach ($this->lists as $killlist)
		{
			$count += $killlist->getCount();
		}
		return $count;
	}

	public function getRealCount()
	{
		$count = 0;
		foreach ($this->lists as $killlist)
		{
			$count += $killlist->getRealCount();
		}
		return $count;
	}

	public function getISK()
	{
		$sum = 0;
		foreach ($this->lists as $killlist)
		{
			$sum += $killlist->getISK();
		}
		return $sum;
	}

	public function getPoints()
	{
		$sum = 0;
		foreach ($this->lists as $killlist)
		{
			$sum += $killlist->getPoints();
		}
		return $sum;
	}
}