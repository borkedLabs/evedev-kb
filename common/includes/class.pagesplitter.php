<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

use EDK\Core\URI;
/**
 * @package EDK
 */
class PageSplitter
{
	/**
	 * Create a PageSplitter
	 *
	 * @param integer $max Total count
	 * @param integer $split Number of lines to show on each page
	 */
	function __construct($max, $split)
	{
		$this->max_ = $max;
		$this->split_ = $split;
	}

	function getSplit()
	{
		return $this->split_;
	}

	function generate()
	{
		global $smarty;
		if (!$this->split_ || $this->max_ / $this->split_ <= 1)
			return;

		$endpage = ceil($this->max_ / $this->split_);
		$args = URI::parseURI();
		if ($page = URI::getArg('page')) {
			if (URI::getArg('page')) {
				foreach ($args as $key => $value) {
					if($value[0] == 'page') {
						unset($args[$key]);
						break;
					}
				}
			}
		} else {
			$page = 1;
		}
		$url = URI::build($args);
		if(strpos($url, '?') === false) {
			$url .= "?";
		} else {
			$url .= "&amp;";
		}

		$smarty->assign('splitter_endpage', $endpage);
		$smarty->assign('splitter_page', $page);
		$smarty->assign('splitter_url', $url);
		
		return $smarty->fetch(get_tpl('pagesplitter'));
	}
}
