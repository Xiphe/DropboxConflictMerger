<?php

namespace Xiphe\DropboxConflictMerger;
use Xiphe as X;

/**
 * Controls the visuals for dbcm.
 *
 * @copyright Copyright (c) 2013 Hannes Diercks
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE
 * @author    Hannes Diercks
 * @version   1.0.0
 * @link      
 * @package   Xiphe\DropboxConflictMerger
 */
class Layout extends Base
{
	private $_additionalViews;

	public static $singleton = true;

	public function init($initArgs = null) {
		if (isset($_REQUEST['action'])) {
			switch ($_REQUEST['action']) {
			case 'resolveSimilar':
				Dropbox::getInstance()->resolveSimilar();
				break;
			case 'logout':
				Dropbox::getInstance()->logout();
				exit;
			case 'merge':
				Dropbox::getInstance()->merge();
				echo 0;
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
		if (isset($this->_additionalViews[$name])) {
			$file = $this->_additionalViews[$name];
		} else {
			$file = dirname(__FILE__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$name.'.php';
		}
		if (file_exists($file)) {
			$HTML = X\HTML::get();
			include $file;
			return true;
		} else {
			return false;
		}
	}

	public function registerViewFile($file) {
		$this->_additionalViews[pathinfo($file, PATHINFO_FILENAME)] = $file;
	}
}