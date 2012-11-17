<?php

namespace Xiphe\DropboxConflictMerger;

/**
 * Basic class to be extended.
 *
 * Provides singleton logic, the result/exit/error mechanics
 * and some handy methods.
 *
 * @copyright Copyright (c) 2012, Hannes Diercks
 * @author    Hannes Diercks <xiphe@gmx.de>
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE
 * @version   1.1.0
 * @link      [Link]
 * @package   [Package]
 */
class Base {

	/**
	 * The response which is serialized and printed on Basics::exit().
	 *
	 * @access private
	 * @var array
	 */
	private static $s_r = array(
		'status' => 'error',
		'msg' => 'nothing happened.',
		'errorCode' => -1
	);

	/**
	 * Setter for the response array.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @return void.
	 */
	final public function set_r($key, $value)
	{
		self::$s_r[$key] = $value;
	}

	/**
	 * The construction
	 *
	 * @access public
	 * @return object
	 */
	final public function __construct($a = null)
	{
		if ($a != 'rrxrQ-4GiT4>G?*r,/S._\1]&/@>f&b') {
			throw new \Exception("Please initiate objects with the static getInstance method.", 1);
		}
	}

	final public static function getInstance($initArgs = null)
	{
		$cls = get_called_class();
		if (isset($cls::$singleton) && $cls::$singleton) {
			if (!is_object($cls::$singleton)) {
				$cls::$singleton = new $cls('rrxrQ-4GiT4>G?*r,/S._\1]&/@>f&b');
				$cls::$singleton->init($initArgs);
			}
			return $cls::$singleton;
		}
		$cls = new $cls('rrxrQ-4GiT4>G?*r,/S._\1]&/@>f&b');
		$cls->init($initArgs);
		return $cls;
	}

	/**
	 * Fall-back for classes that does not need the init function
	 *
	 * @access public
	 * @return void
	 */
	public function init($initArgs = null)
	{
		return null;
	}

	/**
	 * Killer function stops the script and echoes the serialized response
	 *
	 * @access public
	 * @param  string $status    short string describing the status (error|ok|...)
	 * @param  string $msg       longer description of the status, error msg etc.
	 * @param  int    $errorCode unique number for the error.
	 * @return void
	 */
	final public function _exit($status = null, $msg = null, $errorCode = null)
	{
		foreach(array('status', 'msg', 'errorCode') as $k) {
			if($$k !== null) {
				self::$s_r[$k] = $$k;
			}
		}
		
		echo serialize(self::$s_r);
		exit;
	}

	public static function benchmark()
	{
		if (!isset($GLOBALS['benchmarktime'])) {
			$GLOBALS['benchmarktime'] = microtime();
		}

		register_shutdown_function(
			function () {
				$s_mt = explode(" ", $GLOBALS['benchmarktime']);
				$e_mt = explode(" ", microtime());
            	$runtime = (($e_mt[1] + $e_mt[0]) - ($s_mt[1] + $s_mt[0]));
            	$msg = sprintf('Runtime of %s Seconds.', $runtime);

            	if (function_exists('debug')) {
            		debug($msg, 2);
            	} else {
            		echo $msg;
            	}
			}
		);
	}
} ?>