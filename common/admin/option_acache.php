<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
 
use EDK\PageComponent\Options;

/*
* This file contains the generic admin options in the new format
* look here for some examples.
*/
Options::cat('Advanced', 'Cache', 'Page Cache');
Options::fadd('Enable Page Cache', 'cache_enabled', 'checkbox','', array('admin_acache', 'clearPCache'), "Cache created webpages");
Options::fadd('Global lifetime', 'cache_time', 'edit:size:4','','','minutes');
Options::fadd('Ignore pages', 'cache_ignore', 'edit:size:60','','','page1,page2 [no spaces]');
Options::fadd('Page specific lifetime', 'cache_times', 'edit:size:60','','','page:time,page2:time [no spaces]');
Options::fadd('Pages updated every kill', 'cache_update', 'edit:size:60','','','* or page1,page2 [no spaces]');

Options::cat('Advanced', 'Cache', 'Query Cache');
Options::fadd('Enable SQL-Query File Cache', 'cfg_qcache', 'checkbox', '', '','Select only one of file cache or memcache');
Options::fadd('Enable SQL-Query MemCache', 'cfg_memcache', 'checkbox','','','Requires a separate memcached installation');
Options::fadd('Memcached server', 'cfg_memcache_server', 'edit:size:50');
Options::fadd('Memcached port', 'cfg_memcache_port', 'edit:size:8');
Options::fadd('Halt on SQLError', 'cfg_sqlhalt', 'checkbox');

Options::cat('Advanced', 'Cache', 'Even More Caching');
Options::fadd('Killmail Caching enabled','km_cache_enabled','checkbox');
Options::fadd('Object Caching enabled','cfg_objcache','checkbox','','','Advisable for memcached boards.');

Options::cat('Advanced', 'Cache', 'Clear Caches');
Options::fadd('File Cache', 'none', 'custom', array('admin_acache', 'optionClearCaches'), array('admin_acache', 'clearCaches'));
Options::fadd('Kill Summary Cache', 'none', 'custom', array('admin_acache', 'optionClearSum'), array('admin_acache', 'clearSumCache'));

global $mc;
if(defined('DB_USE_MEMCACHE') && DB_USE_MEMCACHE == true && !is_null($mc) && method_exists('Memcache', 'flush'))
{
    Options::fadd('MemCache', 'none', 'custom', array('admin_acache', 'optionFlushMemcached'), array('admin_acache', 'flushMemcached'));
}

class admin_acache
{
    function getKillmails()
    {
		$count = 0;
		if(defined('KB_MAILCACHEDIR'))
		{
			if(is_dir(KB_MAILCACHEDIR))
			{
				if($files = scandir(KB_MAILCACHEDIR))
				{
					foreach($files as $file)
					{
						if (substr($file, 0, 1) != '.') $count++;
					}
					
				}
			}
		}
		return $count;
    }
    function clearPCache()
    {
		CacheHandler::removeByAge('store', 0);
    }
	function optionClearCaches()
	{
		return '<input type="checkbox" name="option_clear_caches" />Clear caches ?';
	}
	function optionClearSum()
	{
		return '<input type="checkbox" name="option_clear_sum" />Clear cache ?';
	}
        function optionFlushMemcached()
	{
		return '<input type="checkbox" name="option_flush_memcached" />Flush MemCache ?';
	}
	function clearCaches()
	{
        if ($_POST['option_clear_caches'] == 'on') {
			CacheHandler::removeByAge('data', 0, true);
			CacheHandler::removeByAge('api', 0, true);
			CacheHandler::removeByAge('store', 0, true);
			CacheHandler::removeByAge('mails', 0, true);
			CacheHandler::removeByAge('img', 0, true);
			CacheHandler::removeByAge('templates_c', 0);
			CacheHandler::removeByAge('SQL', 0, true);
			$_POST['option_clear_caches'] = 'off';
        }
	}
	function clearSumCache()
	{
        if ($_POST['option_clear_sum'] == 'on') {
			$qry = DBFactory::getDBQuery();;
			$qry->execute("DELETE FROM kb3_sum_alliance");
			$qry->execute("DELETE FROM kb3_sum_corp");
			$qry->execute("DELETE FROM kb3_sum_pilot");
			// Clear page and query cache as well since they also contain the
			// summaries.
			CacheHandler::removeByAge('SQL', 0, true);
			CacheHandler::removeByAge('store', 0, true);
			$_POST['option_clear_sum'] == 'off';
        }
	}
        function flushMemcached()
	{
            global $mc;
            // Check for memcached
            if(defined('DB_USE_MEMCACHE') && DB_USE_MEMCACHE == true && !is_null($mc) && method_exists('Memcache', 'flush'))
            {
                $mc->flush();
            }
            $_POST['option_flush_memcached'] = 'off';
	}
}