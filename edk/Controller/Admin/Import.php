<?php
/*
 * @package EDK
 */

namespace EDK\Controller\Admin;

use EDK\Core\Session;
use EDK\Database;
use EDK\Page\Page;

class Import extends Base
{
	
	public function csvAction()
	{
		global $smarty, $menubox;
		
		$page = new Page('Administration - Killmail import');
		$page->setAdmin();

		if (!$_POST['killmail'])
		{
			$html .= '<b>Killmails in same format as export (Comma Separated - csv):</b><br>';
			$html .= '<form id=postform name=postform class=f_killmail method=post action="'.KB_HOST.'/?a=admin_kill_import_csv">';
			$html .= '<textarea class=killmail id=killmail name=killmail cols="55" rows="35"></textarea><br><br>';
			$html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id=submit name=submit type=submit value="Process !"></input>';
			$html .= '</form>';
		}
		else
		{
			// Set delimiter
			$splitter = ',\n\n';
			$killmail = $_POST['killmail'];

			// Replace double quotes with single
			$killmail = str_replace('""', "'", $killmail);

			// Replace \ with nothing
			$killmail = str_replace('\\', "", $killmail);

			// Explodes to array
			$getstrings = explode('"', $splitter . $killmail . $splitter);

			// Set lenght of delimiter
			$delimlen = strlen($splitter);

			// Default
			$instring = 0;

			// String magic :)
			while (list($arg, $val) = each($getstrings))
			{
				if ($instring == 1)
				{
					$result[] = $val;
					$instring = 0;
				}
				else
				{
					if ((strlen($val) - $delimlen - $delimlen) >= 1)
					{
						$temparray = explode($splitter, substr($val, $delimlen, strlen($val) - $delimlen - $delimlen));
						while (list($iarg, $ival) = each($temparray))
						{
							$result[] = trim($ival);
						}
					}
					$instring = 1;
				}
			}
			// Parses killmails one by one.
			foreach ($result as $killmail)
			{
				$parser = new Parser($killmail);
				$killid = $parser->parse(false);
				// Make response
				if ($killid == 0)
				{
					$html .= "Killmail is malformed.<br>";
				}
				elseif ($killid == -1)
				{
					$html .= "That killmail has already been posted <a href=\"?a=kill_detail&kll_id=" . $parser->getDupeID() . "\">here</a>.<br>";
				}
				elseif ($killid == -2)
				{
					$html .= "You are not authorized to post this killmail.<br>";
				}
				elseif ($killid >= 1)
				{
					$html .= "Killmail imported successfully <a href=\"?a=kill_detail&kll_id=" . $killid . "\">here</a>.<br>";
					}
				}
			}

		$page->setContent($html);
		$page->addContext($menubox->generate());
		$page->generate();
	}
	
	public function importAction()
	{
		global $smarty, $menubox;
		
		@set_time_limit(0);

		$page = new Page();
		$page->setAdmin();
		$page->setTitle('Administration - Killmail Importer');

		if (!$_POST['dir'])
		{
			$dir = getcwd();
		}
		if (!$_POST['ext'])
		{
			$ext = '.txt';
		}
		else
		{
			$ext = $_POST['ext'];
		}
		if ($_GET['submit'] == 'Reset')
		{
			unset($_SESSION['kill_import']);
			unset($_POST);
		}

		$html .= "<form id=\"options\" name=\"options\" method=\"post\" action=\"?a=admin_kill_import\">";

		if ($_POST)
		{
			$dir = $_POST['dir'];
			if (!$dir && $_SESSION['kill_import'])
			{
				$dir = $_SESSION['kill_import']['dir'];
				$ext = $_SESSION['kill_import']['ext'];
			}
			if (!strstr($dir, getcwd()))
			{
				$dir = getcwd().$dir;
			}

			if (substr($dir, -1, 1) != '/')
			{
				$dir .= '/';
			}
			if (is_dir($dir))
			{
				$dirh = opendir($dir);
				$i = 0;
				while ($file = readdir($dirh))
				{
					if (strstr($file, $ext))
					{
						$i++;
					}
				}
				if (!$num)
				{
					$num = 200;
				}
				if ($_POST['submit'] != 'Import')
				{
					$html .= "<div class=block-header2>Import Options</div>";
					$html .= "<table class=kb-subtable>";
					$html .= "<tr><td width=120></td><td>'$dir' contains $i files matching '$ext'</td></tr>";
					$html .= "<tr><td width=120><b>Read # mails at once:</b></td><td><input type=text name=num id=num size=3 maxlength=10 value=\"".$num."\"></td></tr>";
					$html .= "<tr><td width=120><b>Start with mail #:</b></td><td><input type=text name=startnum id=startnum size=3 maxlength=10 value=\"0\"></td></tr>";
					$html .= "<tr><td width=120></td><td><input type=submit name=submit value=\"Import\">&nbsp;<input type=submit name=submit value=\"Reset\"></td></tr>";
					$html .= "</table>";
					$_SESSION['kill_import'] = array();
					$_SESSION['kill_import']['dir'] = $dir;
					$_SESSION['kill_import']['count'] = $i;
					$_SESSION['kill_import']['ext'] = $ext;
					$_SESSION['kill_import']['malformed'] = array();
				}
				else
				{
					$_SESSION['kill_import']['do'] = 1;
					$_SESSION['kill_import']['num'] = $_POST['num'];
					$_SESSION['kill_import']['startnum'] = $_POST['startnum'];
				}
			}
			else
			{
				$html .= "'$dir' is not a valid directory<br>\n";
			}
		}
		elseif (!isset($_SESSION['kill_import']['do']))
		{
			$html .= "<div class=block-header2>Scan for Killmails</div>";
			$html .= "<table class=kb-subtable>";
			$html .= "<tr><td width=120><b>Directory:</b></td><td><input type=text name=dir id=dir size=60 maxlength=80 value=\"".$dir."\"></td></tr>";
			$html .= "<tr><td width=120><b>Extension:</b></td><td><input type=text name=ext id=ext size=3 maxlength=10 value=\"".$ext."\"></td></tr>";
			$html .= "<tr><td width=120><b>Attention:</b></td><td>For security reasons only directorys below the main EVE-KB-directory will be read.</td></tr>";
			$html .= "<tr><td width=120></td><td><input type=submit name=submit value=\"Check\"></td></tr>";
			$html .= "</table>";
		}
		$html .= "</form>";

		if (isset($_SESSION['kill_import']['do']))
		{
			$i = 0;
			$p = 0;
			$p_all = 0;
			$posted = 0;
			$posted_all = 0;
			$invalid = 0;
			$inv = 0;
			$num = 0;

			if ( is_dir( $_SESSION['kill_import']['dir'] ) ) {
				$dirh = opendir($_SESSION['kill_import']['dir']);
				while ($file = readdir($dirh))
				{
					if (strstr($file, $_SESSION['kill_import']['ext']))
					{
						$i++;
						if ($i <= $_SESSION['kill_import']['startnum'])
						{
							continue;
						}
						if ($i <= $_SESSION['kill_import']['numcount'])
						{
							continue;
						}
						$num++;
						$read++;
						$mail = file_get_contents($_SESSION['kill_import']['dir'].$file);
						$mail = stripslashes($mail);
						$parser = new Parser($mail);

						$killid = $parser->parse(false);
						if ($killid == 0)
						{
							$inv++;
							$invalid++;
							$html .= "Killmail is malformed: $file.<br>\n";
							$_SESSION['kill_import']['malformed'][] = $file;
							//unlink($dir.$file);
						}
						elseif ($killid == -1)
						{
							$posted++;
							$posted_all++;
							#echo "That $file killmail has already been posted <a href=\"?a=kill_detail&kll_id=" . $parser->dupeid_ . "\">here</a>.<br>\n";
						}
						else
						{
							$p++;
							$p_all++;
						}

						if ($i % 10 == 0)
						{
							$html .= "$i/".$_SESSION['kill_import']['count']." - $num files read, $p new, $posted old, $inv invalid<br>\n";
							$inv = 0;
							$p = 0;
							$posted = 0;
						}
						if ($i >= $_SESSION['kill_import']['count'])
						{
							$html .= "$i/".$_SESSION['kill_import']['count']." - $num files read, $p new, $posted old, $inv invalid<br>\n";
							$_SESSION['kill_import']['numcount'] = $i;
				//                $_SESSION['kill_import']['postall'] = $posted_all;
				//                $_SESSION['kill_import']['p_all'] = $p_all;
							break;
						}
						if ($num >= $_SESSION['kill_import']['num'])
						{
							$html .= "$i/".$_SESSION['kill_import']['count']." - $num files read, $p new, $posted old, $inv invalid<br>\n";
							$_SESSION['kill_import']['numcount'] = $i;
							break;
						}
					}
				}
			} else {
				$html .= 'Killmail folder does not exist. Press reset to check settings.<br/>';
			}

			if ($_SESSION['kill_import']['count'] - $_SESSION['kill_import']['numcount'] > 0)
			{
				$html .= '<meta http-equiv="refresh" content="5; URL=?a=admin_kill_import" />';
				$html .= 'Automatic refresh in 5s<br/>';
				$html .= '<a href="'.KB_HOST.'/?a=admin_kill_import">Read next '.$_SESSION['kill_import']['num'].'</a>&nbsp;<a href="'.KB_HOST.'/?a=admin_kill_import&submit=Reset">Reset</a>';
				$_SESSION['kill_import']['read'] += $read;
				$_SESSION['kill_import']['p_all'] += $p_all;
				$_SESSION['kill_import']['posted_all'] += $posted_all;
				$_SESSION['kill_import']['invalid'] += $invalid;
			}
			else
			{
				$html .= 'Import complete, '.$_SESSION['kill_import']['read'].' files read, '
						 .$_SESSION['kill_import']['p_all'].' kills added, '
						 .$_SESSION['kill_import']['posted_all'].' already posted, '
						 .$_SESSION['kill_import']['invalid'].' malformed<br>';
					$html .= '<hr/>The following files contained malformed mails:<br/>';

					foreach($_SESSION['kill_import']['malformed'] as $mal_file){
						$html .= $mal_file.'<br/>';
					}

				$html .= '<a href="'.KB_HOST.'/?a=admin_kill_import">Ok</a>';
				unset($_SESSION['kill_import']);
			}
		}
		$page->addContext($menubox->generate());
		$page->setContent($html);
		$page->generate();
	}
}