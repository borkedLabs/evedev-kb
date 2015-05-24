<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

use EDK\Core\Config;
use EDK\PageComponent\AdminBox;
use EDK\PageComponent\Box;
use EDK\PageComponent\Options;

// include all admin modules
// this doesnt need to check for itself because its already loaded
$dir = 'common/admin/';
if (is_dir($dir))
{
    if ($dh = opendir($dir))
    {
        while (($file = readdir($dh)) !== false)
        {
            // only load auto-option files
            if (strstr($file, 'option_') && substr($file, -4) == '.php')
            {
                require_once($dir.$file);
            }
        }
        closedir($dh);
    }
}

// load all auto-options from mods
$mods_active = explode(',', Config::get('mods_active'));
$modOverrides = false;
foreach ($mods_active as $mod)
{
    if (file_exists('mods/'.$mod.'/auto_settings.php'))
    {
        include('mods/'.$mod.'/auto_settings.php');
    }
}

$menubox = new AdminBox();

Options::oldMenu('Features', "Campaigns", array(array('a', 'admin_cc',  true),
	array('op', 'view',  false)));
Options::oldMenu('Features', "Standings", array('a', 'admin_standings',  true));//

Options::oldMenu('Appearance', "Map Options", array('a', 'admin_mapoptions', true));

Options::oldMenu('Advanced', "Post Permissions", array('a', 'admin_postperm', true));
Options::oldMenu('Advanced', "Item Values", array('a', 'admin_value_fetch', true));

Options::oldMenu('Features', "Modules", array('a', 'admin_mods', true));

Options::oldMenu('Features', "Feed Syndication", array('a', 'admin_idfeedsyndication', true));
Options::oldMenu('Features', "API Killlog", array('a', 'admin_api', true));
//Options::oldMenu('Features', "Old Feed", array('a', 'admin_feedsyndication', true));
Options::oldMenu('Features', "zKB Fetch", array('a', 'admin_zkbfetch', true));

Options::oldMenu('Maintenance', "Auditing", array('a', 'admin_audit', true));
Options::oldMenu('Maintenance', "Troubleshooting", array('a', 'admin_troubleshooting', true));
Options::oldMenu('Maintenance', "File Verification", array('a', 'admin_verify', true));
Options::oldMenu('Maintenance', "Upgrade", array('a', 'admin_upgrade', true));
Options::oldMenu('Maintenance', "Settings Report", array('a', 'admin_status', true));
Options::oldMenu('Kill Import/Export', "Kill Import - files", array('a', 'admin_kill_import', true));
Options::oldMenu('Kill Import/Export', "Kill Import - csv", array('a', 'admin_kill_import_csv', true));
Options::oldMenu('Kill Import/Export', "Kill Export - files", array('a', 'admin_kill_export', true));
//Options::oldMenu('Kill Import/Export', "Kill Export - csv", array('a', 'admin_kill_export_search', true));
Options::oldMenu('- Logout -', "Logout", array('a', 'logout', true));

#Options::oldMenu('User', 'Titles', '?a=admin_titles');

Options::oldMenu('Appearance', "Top Navigation", array('a', 'admin_navmanager', true));
