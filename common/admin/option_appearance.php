<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */


use EDK\Core\Config;
use EDK\PageComponent\Options;

Options::cat('Appearance', 'Global Options', 'Global Look');
Options::fadd('Banner', 'style_banner', 'select', array('admin_appearance', 'createSelectBanner'), array('admin_appearance', 'changeBanner'));
Options::fadd('Theme', 'theme_name', 'select', array('admin_appearance', 'createSelectTheme'), array('admin_appearance', 'changeTheme'));
Options::fadd('Style', 'style_name', 'select', array('admin_appearance', 'createSelectStyle'), array('admin_appearance', 'changeStyle'));
Options::fadd('Language', 'cfg_language', 'select', array('admin_appearance', 'createLanguage'));

Options::cat('Appearance', 'Global Options', 'Global Options');
Options::fadd('Display standings', 'show_standings', 'checkbox');
Options::fadd('Enable lost item values', 'item_values', 'checkbox');
Options::fadd('Display a link instead of POD on Battlesummary', 'bs_podlink', 'checkbox');
Options::fadd('Include Capsules, Shuttles and Noobships in kills', 'podnoobs', 'checkbox');
Options::fadd('Classify kills for hours:', 'kill_classified', 'edit:size:4', '', '', '0 to disable, 1-24hrs');

Options::cat('Appearance', 'Global Options', 'User Registration');
Options::fadd('Show user-menu on every page', 'user_showmenu', 'checkbox');
Options::fadd('Registration disabled', 'user_regdisabled', 'checkbox');
Options::fadd('Registration password', 'user_regpass', 'edit');
Options::fadd('Allow out-of-game registration', 'user_noigb', 'checkbox');

Options::cat('Appearance', 'Front Page', 'Front Page');
Options::fadd('Combine kills and losses', 'show_comb_home', 'checkbox');
Options::fadd('Fill home page', 'cfg_fillhome', 'checkbox', '', '', 'Include kills from previous week/months to fill home page');
Options::fadd('Display region names', 'killlist_regionnames', 'checkbox');
Options::fadd('Display comment count', 'comments_count', 'checkbox');
Options::fadd('Display involved count', 'killlist_involved', 'checkbox');
Options::fadd('Display clock', 'show_clock', 'checkbox');
Options::fadd('Display Monthly stats', 'show_monthly', 'checkbox', '', '', 'Default is weekly');
Options::fadd('Show ISK loss', 'killlist_iskloss', 'checkbox', '', '', 'Instead of ship type');

Options::cat('Appearance', 'Front Page', 'Kill Summary Tables');
Options::fadd('Display Summary Table', 'summarytable', 'checkbox');
Options::fadd('Display a summary line below a Summary Table', 'summarytable_summary', 'checkbox');
Options::fadd('Display efficiency in the summary line', 'summarytable_efficiency', 'checkbox');

Options::cat('Appearance', 'Front Page', 'Kill Lists');
Options::fadd('Amount of kills listed', 'killcount', 'edit:size:2');

Options::cat('Appearance', 'Kill Details', 'Kill Details');
Options::fadd('Display killpoints', 'kill_points', 'checkbox');
Options::fadd('Display losspoints', 'loss_points', 'checkbox');
Options::fadd('Display totalpoints', 'total_points', 'checkbox');
Options::fadd('Show Total ISK Loss, Damage at top', 'kd_showiskd', 'checkbox');
Options::fadd('Show Top Damage Dealer/Final Blow Boxes', 'kd_showbox', 'checkbox');
Options::fadd('Show involved parties summary', 'kd_showext', 'checkbox');
Options::fadd('Include dropped value in total loss', 'kd_droptototal', 'checkbox');

//Options::fadd('Show T2 items tag', 'kd_ttag', 'checkbox');
//Options::fadd('Show Faction items tag', 'kd_ftag', 'checkbox');
//Options::fadd('Show Deadspace items tag', 'kd_dtag', 'checkbox');
//Options::fadd('Show Officer items tag', 'kd_otag', 'checkbox');
Options::fadd('Show Fitting Panel', 'fp_show', 'checkbox');
Options::fadd('Show Fitting Exports', 'kd_EFT', 'checkbox');
Options::fadd('Limit involved parties', 'kd_involvedlimit', 'edit:size:4', '', '', 'Leave blank for no limit.');

Options::cat('Appearance', 'Kill Details', 'Fitting Panel');
Options::fadd('Panel Theme', 'fp_theme', 'select', array('admin_appearance', 'createPanelTheme'));
Options::fadd('Panel Style', 'fp_style', 'select', array('admin_appearance', 'createPanelStyle'));
Options::fadd('Item Highlight Style', 'fp_highstyle', 'select', array('admin_appearance', 'createHighStyle'));
Options::fadd('Ammo Highlight Style', 'fp_ammostyle', 'select', array('admin_appearance', 'createAmmoStyle'));
Options::fadd('Show Ammo, charges, etc', 'fp_showammo', 'checkbox');
//Options::fadd('Highlight Tech II items', 'fp_ttag', 'checkbox');
//Options::fadd('Highlight Faction items', 'fp_ftag', 'checkbox');
//Options::fadd('Highlight Deadspace items', 'fp_dtag', 'checkbox');
//Options::fadd('Highlight Officer items', 'fp_otag', 'checkbox');

class admin_appearance
{
	function createPanelTheme()
	{
		$sfp_themes = array("tyrannis",
			"tyrannis_blue",
			"tyrannis_darkred",
			"tyrannis_default",
			"tyrannis_revelations");
		$option = array();
		$selected = Config::get('fp_theme');
		foreach($sfp_themes as $theme)
		{
			if($theme == $selected)
			{
				$state = 1;
			}
			else
			{
				$state = 0;
			}
			$options[] = array('value' => $theme, 'descr' => $theme, 'state' => $state);
		}
		return $options;
	}

	function createPanelStyle()
	{
		$sfp_styles = array("Windowed",
			"OldWindow",
			"Border",
			"Faded");
		$option = array();
		$selected = Config::get('fp_style');
		foreach($sfp_styles as $style)
		{
			if($style == $selected)
			{
				$state = 1;
			}
			else
			{
				$state = 0;
			}
			$options[] = array('value' => $style, 'descr' => $style, 'state' => $state);
		}
		return $options;
	}

	function createHighStyle()
	{
		$sfp_highstyles = array("ring",
			"square",
			"round",
			"backglowing",
			"tag",
			"none");
		$option = array();
		$selected = Config::get('fp_highstyle');
		foreach($sfp_highstyles as $style)
		{
			if($style == $selected)
			{
				$state = 1;
			}
			else
			{
				$state = 0;
			}
			$options[] = array('value' => $style, 'descr' => $style, 'state' => $state);
		}
		return $options;
	}

	function createAmmoStyle()
	{
		$sfp_ammostyles = array("solid",
			"transparent",
			"none");
		$option = array();
		$selected = Config::get('fp_ammostyle');
		foreach($sfp_ammostyles as $style)
		{
			if($style == $selected)
			{
				$state = 1;
			}
			else
			{
				$state = 0;
			}
			$options[] = array('value' => $style, 'descr' => $style, 'state' => $state);
		}
		return $options;
	}

	/* Create the selection options for available banners
	 * @return stringHTML for the banner selection dropdown list.
	 */
	function createSelectBanner()
	{
		$options = array();

		if(Config::get('style_banner') == "0") $state = 1;
		else $state = 0;
		$options[] = array('value' => "0", 'descr' => "No banner", 'state' => $state);

		$dir = "banner/";
		if(is_dir($dir))
		{
			if($dh = scandir($dir))
			{
				foreach($dh as $file)
				{
					$file = substr($file, 0);
					if(!is_dir($dir.$file))
					{
						if(Config::get('style_banner') == $file) $state = 1;
						else $state = 0;

						$options[] = array('value' => $file, 'descr' => $file, 'state' => $state);
					}
				}
			}
		}
		return $options;
	}

	/** Create the selection options for available styles in the current theme.
	 *
	 * @return string HTML for the style selection dropdown list.
	 */
	function createSelectStyle()
	{
		$options = array();
		$dir = "themes/".Config::get('theme_name')."/";

		if(is_dir($dir))
		{
			if($dh = scandir($dir))
			{
				foreach($dh as $file)
				{
					if(!is_dir($dir.$file))
					{
						if(substr($file, -4) != ".css") continue;

						if(Config::get('style_name').'.css' == $file) $state = 1;
						else $state = 0;

						$options[] = array('value' => substr($file, 0, -4), 'descr' => substr($file, 0, -4), 'state' => $state);
					}
				}
			}
		}
		return $options;
	}

	/** Create the selection options for available themes.
	 *
	 * @return string HTML for the theme selection dropdown list.
	 */
	function createSelectTheme()
	{
		$options = array();
		$dir = "themes/";

		if(is_dir($dir))
		{
			if($dh = scandir($dir))
			{
				foreach($dh as $file)
				{
					if(is_dir($dir.$file))
					{
						if($file == "." || $file == ".." || $file == ".svn") continue;
						if(Config::get('theme_name') == $file) $state = 1;
						else $state = 0;

						$options[] = array('value' => $file, 'descr' => $file, 'state' => $state);
					}
				}
			}
		}
		return $options;
	}

	/**
	 * Checks if theme has changed and updates page before display.
	 */
	function changeTheme()
	{
		global $themename;
		if(Options::getPrevious('theme_name') == $_POST['option_theme_name']) return;

		$themename = preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['option_theme_name']);
		if(!is_dir("themes/$themename")) $themename = 'default';

		$_POST['option_theme_name'] = $themename;
		Config::set('theme_name', $themename);

		global $smarty;
		$smarty->assign('theme_url', Config::get('cfg_kbhost').'/themes/'.$themename);
		$smarty->template_dir = './themes/'.$themename.'/templates';
		if(!file_exists(KB_CACHEDIR.'/templates_c/'.$themename.'/'))
				mkdir(KB_CACHEDIR.'/templates_c/'.$themename.'/', 0755, true);
		$smarty->compile_dir = KB_CACHEDIR.'/templates_c/'.$themename.'/';
		CacheHandler::removeByAge('templates_c/'.$themename, 0, false);
	}

	/**
	 * Updates style before page is displayed.
	 */
	function changeStyle()
	{
		global $smarty;
		if(Options::getPrevious('theme_name') != $_POST['option_theme_name'])
		{
			$themename = preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['option_theme_name']);
			if(!is_dir("themes/$themename")) $themename = 'default';

			$arr = reset(self::createSelectStyle());

			Config::set('style_name', $arr['value']);
			$_POST['option_style_name'] = $arr['value'];

			$smarty->assign('style', $arr['value']);
		}
		elseif(Options::getPrevious('style_name') != $_POST['option_style_name'])
		{
			$smarty->assign('style', preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['option_style_name']));
		}
	}

	/**
	 * Checks if banner has changed, updates page before display and resets banner size.
	 *
	 * If the banner is changed the stored size is updated and used to display
	 *  the banner image. Smarty variables are updated so display is immediate.
	 */
	function changeBanner()
	{
		global $smarty;
		if(Options::getPrevious('style_banner') == $_POST['option_style_banner'])
				return;
		if($_POST['option_style_banner'] == 0) return;

		$dimensions = getimagesize('banner/'.$_POST['option_style_banner']);
		if(!$dimensions) $dimensions = array(0, 0);

		Config::set('style_banner_x', $dimensions[0]);
		Config::set('style_banner_y', $dimensions[1]);

		$smarty->assign('banner_x', $dimensions[0]);
		$smarty->assign('banner_y', $dimensions[1]);
	}
	public static function createLanguage()
	{
		$options = array();
		$dir = scandir('common/language');
		foreach($dir as $file) {
			if (substr($file, 0, 1) == '.'
					|| substr($file, -4) != '.php') {
				continue;
			}
			if (Config::get('cfg_language') == substr($file, 0, -4)) {
				$state = 1;
			} else {
				$state = 0;
			}
			$options[] = array('value' => substr($file, 0, -4),
				'descr' => substr($file, 0, -4), 'state' => $state);
		}
		return $options;
	}
}
