<?php
namespace Xiphe\DropboxConflictMerger;

$HTML //->s_div('.jumbotron')
->s_div()
	->x_h1('Welcome Stranger', '.welcome')
	->x_p(
		'I can search in your Dropbox for conflicted files and present you a clear way to merge '.
			'them into one file again.',
	    '.lead'
	)
	->x_p(
		'In order to do this you need to allow me full access to your Dropbox.',
	    '.lead'
	)
	->s_form('style=text-align: center;')
		->x_button('Connect', '.btn btn-large btn-success|name=action|value=login')
		->blank(' &nbsp; &nbsp; ')
		->checkbox('style=position: relative; top: -4px;|name=permanent', false)
		->x_label('Keep me logged in.', 'for=permanent|style=display: inline;')
	->end()->br()
	->x_p(
		'The connection will be stored as a cookie in your browser. I will not save any data '.
			'after runtime and will not run any analytics on your Dropbox files. Promise!'
	)
	->x_p(
		'If you do not have an account. Please consider using the following link/button. '
		.'I will get 500MB additional space in my Box if you do it :) Thank you!'
	)
	->x_a('Register at Dropbox', 'href=http://db.tt/nDphHU0|title=Register for a Dropbox account|.btn');

	Dropbox::getInstance()->googleAnalytics();