<?php

namespace Xiphe\DropboxConflictMerger;
use Xiphe as X;

/**
 * Main Class for My Project
 *
 * @copyright Copyright (c) 2013 Hannes Diercks
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
    private $_baseUrl;

    private static $_tmpFolder;
    private static $_oauthLib = 'pear';
    private static $_conflict = '[name] conflicted copy [date]';

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
    public function init($config = null) {
        $this->_encyptionKey = $config['encryptionKey'];
        $this->_baseUrl = $config['baseUrl'];
        $this->_gaTrackingCode = $config['gaTrackingCode'];

        self::$_tmpFolder = dirname(__FILE__).DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR;
        if (self::$_oauthLib == 'pecl') {
            $this->_oauth = new Dropbox_OAuth_PHP(
                $config['consumerKey'],
                $config['consumerSecret']
            );
        } else {
            $this->_oauth = new \Dropbox_OAuth_PEAR(
                $config['consumerKey'],
                $config['consumerSecret']
            );
        }

        $this->_dropbox = new \Dropbox_API($this->_oauth);

        $this->_authenticate();

        // $this->getRawConflicts();

        // $this->computeConficts();
    }

    public function googleAnalytics()
    {
        \Xiphe\HTML::get()    
            ->js("
                var _gaq = _gaq || [];
                    _gaq.push(['_setAccount', '%s']);
                    _gaq.push(['_trackPageview']);

                    (function() {
                        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                    })();
                ",
                null,
                $this->_gaTrackingCode
            );
    }

    public function getConflictStr($for = 'search')
    {
        $str = eden(
            'type',
            Translator::getInstance()->Language->get(self::$_conflict)
        );

        switch ($for) {
        case 'realname':
            $str->replace('[name]', '.*')
                ->replace('[date]', '[^.]+');
            $str = '/ \('.$str.'\)/';
            break;
        case 'computer':

            $str = '/.*\('.$this->getConflictStr('computer').' ([^ ]+)/';

            $str->replace();
            break;
        default:
            $str->replace('[name]', '')
                ->replace('[date]', '')
                ->trim();
            break;
        }

        return $str.'';
    }

    public function getUserName()
    {
        return $this->accountInfo['display_name'];
    }

    public function logout()
    {
        $session = eden('session')->start();
        $cookie = eden('cookie');

        unset($cookie['xiphe_dbcm_auth']);
        unset($session['state']);
        unset($session['oauth_tokens']);
        header('Location: http://www.dropbox.com/');
    }

    public function getRawConflicts($file = null)
    {
        if (empty($file)) {
            $search = $this->getConflictStr();
        } else {
            $search = dirname($file).'/';
            $search .= pathinfo($file, PATHINFO_FILENAME);
            $search .= $this->getConflictStr();
        }
        $this->rawConflicts = $this->_dropbox->search($search);
    }

    public function computeConficts()
    {   
        $this->conflicts = array();
        foreach ($this->rawConflicts as $conflict) {
            $name = basename($conflict['path']);
            // preg_match($this->getConflictStr('computer'), $name, $m);
            // preg_match('/.*\('.$this->getConflictStr('computer').' ([^ ]+)/', $name, $m);
            // $computer = $m[1];
            $realName = preg_replace($this->getConflictStr('realname'), '', $name);
            // $realName = preg_replace('/ \('.$this->conflict().' [^ ]+ [^.]+\)/', '', $name);
            $origin = dirname($conflict['path']).'/'.$realName;

            // $conflict['computer'] = $computer;
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
        $session = eden('session')->start();
        $cookie = eden('cookie');

        if (isset($cookie['xiphe_dbcm_auth'])) {
            $encrypedTokens = $cookie['xiphe_dbcm_auth'];

            /*
             * Decode token-data from cookie.
             */
            $tokens = (array) json_decode(X\THETOOLS::decrypt($encrypedTokens, $this->_encyptionKey));

            /*
             * check if ip address is still the same - otherwise delete cookie and reauth.
             */
            if ($tokens['ip'] !== $_SERVER['REMOTE_ADDR']) {
                $_GET['page'] = 'loginError';
                unset($cookie['xiphe_dbcm_auth']);
                unset($session['state']);
                return;
            } else {
                unset($tokens['ip']);
            }

            /*
             * update the cookie lifetime.
             */
            $cookie->set(
                'xiphe_dbcm_auth',
                $encrypedTokens,
                time()+60*60*24*182
            );
            $state = 3;
        } elseif (isset($session['state'])) {
            $state = $session['state'];
        } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
            $callback = $this->_baseUrl;

            if (isset($_POST['permanent']) && $_POST['permanent'] == 'on') {
                X\THETOOLS::filter_urlQuery($callback, null, null, array('permanent' => 'on'));
            }
            $state = 1;
        } else {
            $state = -1;
        }


        switch($state) {
        case -1:
            $_GET['page'] = 'welcome';
            return;

        /* In this phase we grab the initial request tokens
           and redirect the user to the 'authorize' page hosted
           on dropbox */
        case 1:
            header('Content-Type: text/plain');
            $tokens = $this->_oauth->getRequestToken();

            $session['state'] = 2;
            $session['oauth_tokens'] = $tokens;
            header('Location: '.$this->_oauth->getAuthorizeUrl($callback));
            exit;

        /* In this phase, the user just came back from authorizing
           and we're going to fetch the real access tokens */
        case 2:
            try {
                $this->_oauth->setToken($session['oauth_tokens']);
                $tokens = $this->_oauth->getAccessToken();
            } catch (\HTTP_OAuth_Consumer_Exception_InvalidResponse $e) {
                $_GET['page'] = 'loginError';
                unset($session['state']);
                break;
            }

            if (isset($_GET['permanent']) && $_GET['permanent'] === 'on') {
                $tokens['ip'] = $_SERVER['REMOTE_ADDR'];
                $cookie->set(
                    'xiphe_dbcm_auth',
                    X\THETOOLS::encrypt(json_encode($tokens), $this->_encyptionKey),
                    time()+60*60*24*182
                );

                unset($session['state']);
            } else {
                $session['state'] = 3;
                $session['oauth_tokens'] = $tokens;
            }
            // There is no break here, intentional

        /* This part gets called if the authentication process
           already succeeded. We can use our stored tokens and the api 
           should work. Store these tokens somewhere, like a database */
        case 3 :
            if (!isset($tokens)) {
                $tokens = $session['oauth_tokens'];
            }
            $this->_oauth->setToken($tokens);
            break;

        default:
            $_GET['page'] = 'loginError';
            unset($session['state']);
            return;
        }

        $this->accountInfo = $this->_dropbox->getAccountInfo();
        eden('event')->trigger('xiphe_dbcm_connected', $this->accountInfo, $this->_dropbox);
    }
}