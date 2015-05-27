<?php
/**
 * $Date$
 * $Revision$
 * $HeadURL$
 * @package EDK
 */
namespace EDK\Controller;

use EDK\Cache\Cache;
use EDK\Core\Event;
use EDK\Core\Language;
use EDK\Core\Session;
use EDK\Core\URI;
use EDK\Page\Page;

/*
 * @package EDK
 */
class Login extends Base
{

	/** @var Page The Page object used to display this page. */
	public $page;

	function indexAction()
	{
		$this->queue("start");
		$this->queue("content");
		$this->generate();
		
		Cache::generate();
	}
	
	public function generate()
	{
		Event::call("login_assembling", $this);
		$html = $this->assemble();
		$this->page->setContent($html);

		$this->page->generate();
	}

	function start()
	{
		$this->page = new Page(Language::get('page_login'));
	}

	function content()
	{
		global $smarty;
		if (trim($_POST['usrpass'])) {
			if ($_POST['usrlogin'] == '' && $_POST['usrpass'] == ADMIN_PASSWORD
					&& substr(ADMIN_PASSWORD, 0, 3) != '$1$'
					&& substr(ADMIN_PASSWORD, 0, 3) != '$2$'
					&& substr(ADMIN_PASSWORD, 0, 3) != '$2a$') {
				@chmod("kbconfig.php", 0660);
				if (!is_writeable("kbconfig.php")) {
					$smarty->assign('error',
							'Admin password is unencrypted and '.
							'kbconfig.php is not writeable. Either encrypt the admin '.
							'password or set kbconfig.php writeable.');
				} else {
					$kbconfig = file_get_contents('kbconfig.php');
					$newpwd = preg_replace('/(\$|\\\\)/', '\\\\$1', crypt(ADMIN_PASSWORD));
					$kbconfig = preg_replace('/define\s*\(\s*[\'"]ADMIN_PASSWORD[\'"]'
							.'[^)]*\)/', "define('ADMIN_PASSWORD', '"
							.$newpwd."')", $kbconfig);
					file_put_contents("kbconfig.php", trim($kbconfig));
					chmod("kbconfig.php", 0440);

					Session::create(true);

					session_write_close();
					header('Location: '.html_entity_decode(URI::page('admin')));
					die;
				}
			} else if ($_POST['usrlogin'] == ''
					&& crypt($_POST['usrpass'], ADMIN_PASSWORD) == ADMIN_PASSWORD) {
				Session::create(true);

				session_write_close();
				$page = preg_replace('/[^a-zA-Z0-9-_]/', '', URI::getArg("page", 1));
				$page = $page ? $page : "admin";
				header('Location: '.html_entity_decode(URI::page($page)));
				die;
			} else {
				$result = \user::login($_POST['usrlogin'], $_POST['usrpass']);
				if ($result) {
					header('Location: '.html_entity_decode(URI::page('home')));
					die;
				} else {
					$smarty->assign('error',
							'Login error, please check your username'
							.' and password.');
				}
			}
		}

		return $smarty->fetch(get_tpl('user_login'));
	}
}
