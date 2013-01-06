<?php
/*
 Dropbox Conflict Merger
 Copyright (C) 2013 Hannes Diercks

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
 * Error Reporting. (Should be uncommented in production.)
 */
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/* 
 * Uncomment the following line for benchmarking the run time of the script
 */
// $GLOBALS['benchmarktime'] = microtime();

/*
 * Start the application.
 */
include 'bootstrap.php';

/*
 * Make some noise.
 */
include 'header.php';
Xiphe\DropboxConflictMerger\Layout::getInstance()->printPage();
include 'footer.php';