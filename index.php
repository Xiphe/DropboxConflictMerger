<?php
/*
 Dropbox Conflict Merger
 Copyright (C) 2012 Hannes Diercks

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

/* 
 * Uncomment the following line for benchmarking the run time of the script
 */
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
$GLOBALS['benchmarktime'] = microtime();

include 'src/Xiphe/autoload.php';
include 'Dropbox/autoload.php';
include 'src/php-diff/autoload.php';
include 'src/Xiphe/globaldebug.php';
/*
 * Add other autoloader here.
 */

Xiphe\DropboxConflictMerger\Base::benchmark();
$baseUrl = 'http://localhost/DropboxConflictMerger/';
$HTML = new Xiphe\HTML($baseUrl);

Xiphe\DropboxConflictMerger\Dropbox::getInstance(array(
	'consumerKey' => '',
	'consumerSecret' => ''
));

$Request = Xiphe\DropboxConflictMerger\Request::getInstance();

/*
 * Start the ship!
 */

include 'header.php';
$Request->printPage();

$HTML->close('.container-narrow')
	->jQuery()
	->js('./res/js/bootstrap.min.js')
	->js('./res/js/phpdiffmerge.js')
->close('all');
?>