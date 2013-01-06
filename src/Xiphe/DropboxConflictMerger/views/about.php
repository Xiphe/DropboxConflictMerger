<?php

$HTML->x_h2('About')
	->x_p(
		'Hey, my name is Hannes aka Xiphe and i wrote this tool to solve an annoying problem that i have. '.
		'I store my local development-htdocs folder inside my dropbox because i switch a lot between the three '.
		'computers i use (Work, Home & MacBook).'
	)->x_p(
		'This often results in conflicted dropbox files. They occur when a file is change at two locations '.
		'before they were synced with the dropbox servers. Maybe the dropbox app was not running or the computer '.
		'was shut down before the sync process finished.'
	)->x_p(
		'It got really annoying to check all this conflicts by myself so i decided to build a tool for it. '.
		'And here it is :)'
	)->x_p(
		'The Dropbox Conflict Merger will search your entire dropbox for files containing the [...]conflicted copy[...] '.
		'string and present you a list with all of them. You then can click an entry inside this list and present '.
		'you a clean dialog to solve the conflict.'
	)
	->x_h3(
		'Props'
	)->x_p(
		'This would have been much harder without the following projects.'
	)->sg_ul()
		->x_li(
			'%s - Dropbox Client Library for PHP',
			null,
			$HTML->ri_a('Dropbox-PHP', 'http://www.dropbox-php.com/')
		)
		->x_li(
			'%s - from Chris Boulton',
			null,
			$HTML->ri_a('PHP Diff Class', 'https://github.com/chrisboulton/php-diff')
		)
		->li(
			'%s - <3',
			null,
			$HTML->ri_a('Eden PHP Library', 'http://www.eden-php.com/')
		)
		->x_li(
			'%s - from Twitter',
			null,
			$HTML->ri_a('Bootstrap', 'http://twitter.github.com/bootstrap/')
		)
		->x_li(
			'%s - for development',
			null,
			$HTML->ri_a('FirePHP', 'http://www.firephp.org/')
		)
	->end('ul')
;
