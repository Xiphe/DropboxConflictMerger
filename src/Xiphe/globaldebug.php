<?php
/**
 * File for global debugging through THEDEBUG
 *
 * @category debug
 * @package  Xiphe
 * @author   Hannes Diercks <xiphe@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE
 * @link     https://github.com/Xiphe/
 */

if (class_exists('Xiphe\THEDEBUG')) {

	/*
	 * Register THEDEBUG Global.
	 */
	$GLOBALS['THEDEBUG'] = new Xiphe\THEDEBUG;

	if (!function_exists('debug')) {
		/**
		 * Default Debug through THEDEBUG.
		 *
		 * @return void
		 */
		function debug() {
			Xiphe\THEDEBUG::_set_btDeepth(7);
			call_user_func_array(array('Xiphe\THEDEBUG', 'debug'), func_get_args());
			Xiphe\THEDEBUG::_reset_btDeepth();
		}
	}

	if (!function_exists('diebug')) {
		/**
		 * Debug and die.
		 *
		 * @return void
		 */
		function diebug() {
			Xiphe\THEDEBUG::_set_btDeepth(7);
			call_user_func_array(array('Xiphe\THEDEBUG', 'diebug'), func_get_args());
		}
	}

	if (!function_exists('rebug')) {
		/**
		 * Returns the debugged variable.
		 *
		 * @return mixed
		 */
		function rebug() {
			Xiphe\THEDEBUG::_set_btDeepth(7);
			$r = call_user_func_array(array('Xiphe\THEDEBUG', 'rebug'), func_get_args());
			Xiphe\THEDEBUG::_reset_btDeepth();
			return $r;
		}
	}

	if (!function_exists('countbug')) {
		/**
		 * Counts the call of the function and prints the amount at the end of the script.
		 *
		 * @return void
		 */
		function countbug() {
			Xiphe\THEDEBUG::_set_btDeepth(7);
			call_user_func_array(array('Xiphe\THEDEBUG', 'countbug'), func_get_args());
			Xiphe\THEDEBUG::_reset_btDeepth();
		}
	}

	if (!function_exists('deprecated')) {
		/**
		 * Function to mark another function as deprecated.
		 *
		 * @param string  $alternative name of the alternative for the deprecated function.
		 * @param boolean $contunue    whether or not to continue the script.
		 * @param integer $bto         backtrace offset.
		 *
		 * @return void
		 */
		function deprecated($alternative, $contunue = true, $bto = 0) {
			Xiphe\THEDEBUG::_set_btDeepth(7);
			$bto = $bto+2;
			return call_user_func_array(
				array('Xiphe\THEDEBUG', 'deprecated'),
				array($alternative, $contunue, $bto)
			);
			Xiphe\THEDEBUG::_reset_btDeepth();
		}
	}

	/*
	 * If Wordpress is not available register shutdown functions.
	 *
	 * (Otherwise the shutdown hook will be registered in THEWPMASTER)
	 */
	if (!class_exists('\WP')) {
		register_shutdown_function(array('Xiphe\THEDEBUG', 'print_debugcounts'));
		if (Xiphe\THEDEBUG::get_mode() === 'summed') {
			register_shutdown_function(array('Xiphe\THEDEBUG', 'print_debug'));
		}
	};

} else {
	$GLOBALS['THEDEBUG'] = false;
}