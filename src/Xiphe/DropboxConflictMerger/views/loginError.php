<?php

$HTML->x_h2('Sorry!')
	->x_p(
		'An Error occurred when i tried to connect to dropbox :( Please %s or %s. Thank you.',
		null,
		$HTML->rxi_a('retry', array('href' => $HTML->getOption('baseUrl'))),
		$HTML->rxi_a('write a bug report', '')
	);