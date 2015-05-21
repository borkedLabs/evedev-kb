<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

 use EDK\Database\NormalQuery;
 use EDK\Database\CachedQuery;
 use EDK\Database\MemCachedQuery;
 
/**
 * Factory class to create dbquery objects.
 * @package EDK
 */
class DBFactory
{
	/**
	 * Create and return a db query object.
	 *
	 * @param boolean $forceNormal true to disable cached queries
	 * 
	 * @return DBBaseQuery
	 */
	public static function getDBQuery($forceNormal = false)
	{
		if (defined('DB_USE_MEMCACHE') && DB_USE_MEMCACHE == true)
		{
			return new MemCachedQuery($forceNormal);
		}
		else if (defined('DB_USE_QCACHE') && DB_USE_QCACHE == true)
		{
			return new CachedQuery($forceNormal);
		}
		else
		{
			return new NormalQuery();
		}
	}
}