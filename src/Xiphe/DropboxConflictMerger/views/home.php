<?php
namespace Xiphe\DropboxConflictMerger;

$HTML->xs_div('.jumbotron')
	->x_h1('Hello %s ', '.welcome', Dropbox::getInstance()->getUserName())
	->x_p('Click the following Button to let me search for Conflicts in your Dropbox.', '.lead')
	->x_a('Search Conflicts*', '.btn btn-large btn-success|%?page\=listConflicts')
	->x_p('*will take some time.');