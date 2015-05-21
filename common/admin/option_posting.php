<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */

use EDK\Core\Config;

options::cat('Advanced', 'Posting Options', 'Posting Options');

options::fadd('Enable Comments', 'comments', 'checkbox');
options::fadd('Require password for Comments', 'comments_pw', 'checkbox');
options::fadd('Forbid killmail posting', 'post_forbid', 'checkbox');
options::fadd('Forbid CREST link posting', 'post_crest_forbid', 'checkbox');
options::fadd('Require password for CREST link posting', 'crest_pw_needed', 'checkbox');

options::fadd('Killmail post password', 'post_password', 'password', '', array('admin_posting', 'setPostPassword'));
options::fadd('CREST link post password', 'post_crest_password', 'password', '', array('admin_posting', 'setCrestPostPassword'));
options::fadd('Comment post password', 'comment_password', 'password', '', array('admin_posting', 'setCommentPassword'));
;
options::fadd('Disallow any killmails before', 'filter_date', 'custom', array('admin_posting', 'dateSelector'), array('admin_posting', 'postDateSelector'));

class admin_posting
{
    function dateSelector()
    {
        $apply = Config::get('filter_apply');
        $date = Config::get('filter_date');

    	if ($date > 0)
        {
    		$date = getdate($date);
    	}
        else
        {
    		$date = getdate();
    	}
    	$html = "<input type=\"text\" name=\"option_filter_day\" id=\"option_filter_day\" style=\"width:20px\" value=\"{$date['mday']}\"/>&nbsp;";
    	$html .= "<select name=\"option_filter_month\" id=\"option_filter_month\">";
    	for ($i = 1; $i <= 12; $i++)
        {
    		$t = gmmktime(0, 0, 0, $i, 1, 1980);
    		$month = gmdate("M", $t);
    		if($date['mon'] == $i)
            {
                $selected = " selected=\"selected\"";
            }
            else
            {
                $selected = "";
            }

    		$html .= "<option value=\"$i\"$selected>$month</option>";
    	}
    	$html .= "</select>&nbsp;";

    	$html .= "<select name=\"option_filter_year\" id=\"option_filter_year\">";
    	for ($i = gmdate("Y")-7; $i <= gmdate("Y"); $i++)
        {
    		if ($date['year'] == $i)
            {
                $selected = " selected=\"selected\"";
            }
            else
            {
                $selected = "";
            }
    		$html .= "<option value=\"$i\"$selected>$i</option>";
    	}
    	$html .= "</select>&nbsp;";
    	$html .= "<input type=\"checkbox\" name=\"option_filter_apply\" id=\"option_filter_apply\"";
    	if ($apply)
        {
            $html .= " checked=\"checked\"";
        }
    	$html .= "/>Apply&nbsp;";
    	return $html;
    }

    function postDateSelector()
    {
        if ($_POST['option_filter_apply'] == 'on')
        {
            Config::set('filter_apply', '1');
            Config::set('filter_date', gmmktime(0, 0, 0, $_POST['option_filter_month'], ($_POST['option_filter_day'] > 31 ? 31 : $_POST['option_filter_day']), $_POST['option_filter_year']));
        }
        else
        {
        	Config::set('filter_apply', '0');
        	Config::set('filter_date', 0);
        }

    }
	function makePassword($pwd)
	{
		return crypt($pwd);
	}
	function passwordChanged($pwd, $oldpwd)
	{
		return !($pwd == '' ||
			crypt($pwd, $oldpwd) == $oldpwd
			|| ($pwd == $oldpwd && substr($oldpwd,0,3) == '$1$'));
	}
	function setPostPassword()
	{
		if(admin_posting::passwordChanged($_POST['option_post_password'],Config::get('post_password')))
			Config::set('post_password', admin_posting::makePassword($_POST['option_post_password']));
	}
	function setCrestPostPassword()
	{
		if(admin_posting::passwordChanged($_POST['option_post_crest_password'],Config::get('post_crest_password')))
			Config::set('post_crest_password', admin_posting::makePassword($_POST['option_post_crest_password']));
	}
	function setCommentPassword()
	{
		if(admin_posting::passwordChanged($_POST['option_comment_password'],Config::get('comment_password')))
			Config::set('comment_password', admin_posting::makePassword($_POST['option_comment_password']));
	}
	function createCommentQ()
	{
		if(Config::get('comment_password')) $pwd = 'SET';
		else $pwd = '';
		return '<input type="text" id="option_comment_password" name="option_comment_password" value="'.$pwd.'" size="20" maxlength="20" />';
	}
	function createPostQ()
	{
		if(Config::get('post_password')) $pwd = 'SET';
		else $pwd = '';
		return '<input type="text" id="option_post_password" name="option_post_password" value="'.$pwd.'" size="20" maxlength="20" />';
	}
	function reload()
	{
		header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
		die;
	}
}

?>
