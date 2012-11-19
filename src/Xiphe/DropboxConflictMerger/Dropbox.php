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
class Dropbox extends Base
{

    private $_oauth;
    private $_dropbox;

    private static $_tmpFolder;
    private static $_oauthLib = 'pear';
    private static $_lang = array(
        'de_DE' => array(
            'conflict' => '(In Konflikt stehende Kopie von',
            'computer' => '/.*\(In Konflikt stehende Kopie von ([^ ]+)/',
            'origin' => '/ \(In Konflikt stehende Kopie von [^ ]+ [^.]+\)/'
        )

    );

    /**
     * Singleton holder.
     *
     * @access public
     * @var object
     */
    public static $singleton = true;

    public $rawConflicts;
    public $conflicts;

    /**
     * The initiation
     *
     * @access public
     * @return void
     */
    public function init($keys = null) {
        @session_start();

        self::$_tmpFolder = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
        if (self::$_oauthLib == 'pecl') {
            $this->_oauth = new Dropbox_OAuth_PHP(
                $keys['consumerKey'],
                $keys['consumerSecret']
            );
        } else {
            $this->_oauth = new \Dropbox_OAuth_PEAR(
                $keys['consumerKey'],
                $keys['consumerSecret']
            );
        }

        $this->_dropbox = new \Dropbox_API($this->_oauth);

        $this->_authenticate();

        // $this->getRawConflicts();

        // $this->computeConficts();
    }

    public function getUserName()
    {
        $info = $this->_dropbox->getAccountInfo();
        return $info['display_name'];
    }

    public function getRawConflicts($file = null)
    {
        if (empty($file)) {
            $search = self::$_lang['de_DE']['conflict'];
        } else {
            $search = dirname($file).'/';
            $search .= pathinfo($file, PATHINFO_FILENAME);
            $search .= self::$_lang['de_DE']['conflict'];
        }
        $this->rawConflicts = $this->_dropbox->search($search);
        debug($this->rawConflicts, 'conflicts');
    }

    public function computeConficts()
    {   
        $this->conflicts = array();
        foreach ($this->rawConflicts as $conflict) {
            $name = basename($conflict['path']);
            preg_match(self::$_lang['de_DE']['computer'], $name, $m);
            $computer = $m[1];
            $realName = preg_replace(self::$_lang['de_DE']['origin'], '', $name);
            $origin = dirname($conflict['path']).'/'.$realName;

            $conflict['computer'] = $computer;
            $conflict['real_name'] = $realName;

            if (strpos($conflict['mime_type'], 'text/') === 0) {
                $this->conflicts['text'][$origin][] = $conflict;
            } else {
                $this->conflicts[$conflict['mime_type']][$origin][] = $conflict;
            }
        }
    }

    public function getConflicts()
    {
        if (!is_array($this->conflicts)) {
            $this->getRawConflicts();
            $this->computeConficts();
        }
        return $this->conflicts;
    }

    public function initResolve($path, &$files)
    {
        $this->conflicts = array();
        $i = 0;
        foreach ($files as $file) {
            try {
                $f = $this->_dropbox->getMetaData($path.$file);
                $f['content'] = $this->_dropbox->getFile($path.$file);
            } catch(\Dropbox_Exception_NotFound $e) {
                continue;
            }
            $this->conflicts[] = $f;
            if ($i == 1) {
                break;
            }
            $i++;
        }
        $files = array_splice($files, 2);
        return $this->conflicts;
    }

    public function resolveSimilar()
    {
        if (dirname($_POST['keep']).'/' !== $_GET['path']) {
            $_GET['error'] = 'Invalid Path';
            header('Location: '.X\HTML\core\Config::get('baseUrl').'?'.http_build_query($_GET));
            exit;
        }

        $delete = array_flip($_POST['files']);
        unset($delete[basename($_POST['keep'])]);
        $delete = $_GET['path'].key($delete);
        $r = $this->_dropbox->delete($delete);
        header('Location: '.X\HTML\core\Config::get('baseUrl').'?'.http_build_query($_GET));
        exit;
    }


    public function justLineEndings()
    {
        if (preg_replace('/\r\n|\r/', "\n", $this->conflicts[0]['content'])
            === preg_replace('/\r\n|\r/', "\n", $this->conflicts[1]['content'])
        ) {
            return true;
        }
        return false;
    }

    public function getDiff()
    {
        $a = explode("\n", preg_replace('/\r\n|\r/', "\n", $this->conflicts[0]['content']));
        $b = explode("\n", preg_replace('/\r\n|\r/', "\n", $this->conflicts[1]['content']));

        $options = array(
            //'ignoreWhitespace' => true,
            //'ignoreCase' => true,
        );

        // Initialize the diff class
        $Diff = new \Diff($a, $b, $options);
        $Renderer = new \Diff_Renderer_Html_SideBySide;
        echo $Diff->render($Renderer);
        X\HTML::get()->script('var fullleft='.json_encode($a).', fullright='.json_encode($b).';');
    }

    public function merge()
    {
        if (dirname($_POST['name']).'/' !== $_GET['path']) {
            $this->_exit('Error', 'Invalid Path');
        }

        $file = implode("\n", $_POST['merge']);
        $tmp = tempnam(sys_get_temp_dir(), 'dcm');
        $tmpDbFile = $_POST['name'].' - merged by Xiphes Dropbox Conflict Merger';

        file_put_contents($tmp, $file);
        // debug($tmpDbFile, 'add');
        $this->_dropbox->putFile($tmpDbFile, $tmp);

        foreach ($_POST['currentFiles'] as $delete) {
            $delete = $_GET['path'].$delete;
            // debug($delete, 'delete');
            $r = $this->_dropbox->delete($delete);
        }
        unlink($tmp);
        // debug($tmpDbFile, 'rename');
        // debug($_POST['name'], 'to');
        $this->_dropbox->copy($tmpDbFile, $_POST['name']);
        $r = $this->_dropbox->delete($tmpDbFile);

        // debug($_REQUEST);
        $this->_exit('OK', 'Merge complete!');
    }

    private function _authenticate()
    {
        if (isset($_SESSION['state'])) {
            $state = $_SESSION['state'];
        } else {
            $state = 1;
        }

        switch($state) {
        /* In this phase we grab the initial request tokens
           and redirect the user to the 'authorize' page hosted
           on dropbox */
        case 1 :
            header('Content-Type: text/plain');
            $tokens = $this->_oauth->getRequestToken();

            // Note that if you want the user to automatically redirect back, you can
            // add the 'callback' argument to getAuthorizeUrl.
            $_SESSION['state'] = 2;
            $_SESSION['oauth_tokens'] = $tokens;
            // echo $this->_oauth->getAuthorizeUrl();
            header('Location: '.$this->_oauth->getAuthorizeUrl());
            exit;

        /* In this phase, the user just came back from authorizing
           and we're going to fetch the real access tokens */
        case 2 :
            $this->_oauth->setToken($_SESSION['oauth_tokens']);
            $tokens = $this->_oauth->getAccessToken();
            $_SESSION['state'] = 3;
            $_SESSION['oauth_tokens'] = $tokens;
            // There is no break here, intentional

        /* This part gets called if the authentication process
           already succeeded. We can use our stored tokens and the api 
           should work. Store these tokens somewhere, like a database */
        case 3 :
            $this->_oauth->setToken($_SESSION['oauth_tokens']);
            break;
        }

    }
}