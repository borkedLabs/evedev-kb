<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
use EDK\Core\Config;
use EDK\Page\Page;

//TODO: Make a useful registration mod
$page = new Page('User - Registration');

if (Config::get('user_regdisabled'))
{
    $page->error('Registration has been disabled.');
    return;
}

if (isset($_POST['submit']))
{
    $error = false;
    if (Config::get('user_regpass'))
    {
        if ($_POST['regpass'] != Config::get('user_regpass'))
        {
            $smarty->assign('error', 'Registration password does not match.');
            $error = true;
        }
    }

    if (!$_POST['usrlogin'])
    {
        $smarty->assign('error', 'You missed to specify a login.');
        $error = true;
    }

    if (!$_POST['usrpass'])
    {
        $smarty->assign('error', 'You missed to specify a password.');
        $error = true;
    }

    if (strlen($_POST['usrpass']) < 3)
    {
        $smarty->assign('error', 'Your password needs to have at least 4 chars.');
        $error = true;
    }

    if (!$error)
    {
        $pilot = null;
        $id = null;
        user::register(\EDK\Core\EDK::slashfix($_POST['usrlogin']), \EDK\Core\EDK::slashfix($_POST['usrpass']), $pilot, $id);
        $page->setContent('Account registered.');
        $page->generate();
        return;
    }
}


$page->setContent($smarty->fetch(get_tpl('user_register')));
$page->generate();