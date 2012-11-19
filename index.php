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

if (file_exists('config.php')) {
	include 'config.php';
} else {
	include 'header.php';
	$HTML->h2('Error: Configuration array not found!')
	->p('There should be a file called config.php in the root folder. Check if there is a config-sample.php and rename it to config.php.');
	include 'footer.php';
	die();
}

Xiphe\DropboxConflictMerger\Dropbox::getInstance($config);

$Request = Xiphe\DropboxConflictMerger\Request::getInstance();

/*
 * Start the ship!
 */

include 'header.php';
$Request->printPage();
include 'footer.php';
?>