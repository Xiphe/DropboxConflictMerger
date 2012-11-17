<?php

namespace Xiphe\DropboxConflictMerger;
use Xiphe as X;

/**
 * Main Class for My Project
 *
 * @copyright Copyright (c) 2012 Hannes Diercks
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE
 * @author    Hannes Diercks
 * @version   1.0.0
 * @link      
 * @package   Xiphe\DropboxConflictMerger
 */
class Request extends Base
{
	public function init($initArgs = null) {
		if (isset($_REQUEST['action'])) {
			switch ($_REQUEST['action']) {
			case 'resolveSimilar':
				Dropbox::getInstance()->resolveSimilar();
				break;
			case 'logout':
				@session_start();
				unset($_SESSION['state']);
				unset($_SESSION['oauth_tokens']);
				header('Location: '.X\HTML\core\Config::get('baseUrl'));
				exit;
			}
		}
	}

	public function printPage()
	{
		if (empty($_GET['page']) || !$this->view($_GET['page'])) {
			$this->view('home');
		}
	}

	public function view($name)
	{
		$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$name.'.php';
		if (file_exists($file)) {
			$HTML = X\HTML::get();
			include $file;
			return true;
		} else {
			return false;
		}
	}
}