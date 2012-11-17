<?php
namespace Xiphe\DropboxConflictMerger;

$HTML->s_div('.jumbotron')
	->h1('Hello %s ', '.welcome', Dropbox::getInstance()->getUserName())
	->p('Click the following Button to let me search for Conflicts in your Dropbox.', '.lead')
	->a('Search Conflicts', '.btn btn-large btn-success|%?page\=listConflicts');