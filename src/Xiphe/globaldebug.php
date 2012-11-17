<?php
$GLOBALS['THEDEBUG'] = new Xiphe\THEDEBUG;

if( !function_exists( 'debug' ) ) {
	function debug() {
		Xiphe\THEDEBUG::_set_btDeepth( 7 );
		call_user_func_array( array('Xiphe\THEDEBUG', 'debug' ), func_get_args() );
		Xiphe\THEDEBUG::_reset_btDeepth();
	}
}
if( !function_exists( 'diebug' ) ) {
	function diebug() {
		Xiphe\THEDEBUG::_set_btDeepth( 7 );
		call_user_func_array( array('Xiphe\THEDEBUG', 'diebug' ), func_get_args() );
	}
}
if( !function_exists( 'rebug' ) ) {
	function rebug() {
		Xiphe\THEDEBUG::_set_btDeepth( 7 );
		$r = call_user_func_array( array( 'Xiphe\THEDEBUG', 'rebug' ), func_get_args() );
		Xiphe\THEDEBUG::_reset_btDeepth();
		return $r;
	}
}
if( !function_exists( 'countbug' ) ) {
	function countbug() {
		Xiphe\THEDEBUG::_set_btDeepth( 7 );
		call_user_func_array(array('Xiphe\THEDEBUG', 'countbug'), func_get_args() );
		Xiphe\THEDEBUG::_reset_btDeepth();
	}
}

if( !function_exists( 'deprecated' ) ) {
	function deprecated( $alternative, $contunue = true, $bto = 0 ) {
		Xiphe\THEDEBUG::_set_btDeepth( 7 );
		$bto = $bto+2;
		return call_user_func_array(
			array('Xiphe\THEDEBUG', 'deprecated'),
			array( $alternative, $contunue, $bto )
		);
		Xiphe\THEDEBUG::_reset_btDeepth();
	}
}
if( !class_exists('\WP') ) {
	register_shutdown_function( array( 'Xiphe\THEDEBUG', 'print_debugcounts' ) );
	if( Xiphe\THEDEBUG::get_mode() === 'summed' ) {
		register_shutdown_function( array('Xiphe\THEDEBUG', 'print_debug' ) );
	}
}