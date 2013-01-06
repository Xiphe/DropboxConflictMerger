<?php
$HTML->h2('Sorry!')
	->p('I do not have translation files for your country, jet.', '.lead')
	->p('If you\'d like to help you can tell me the filename of a conflicted copy '.
		'in your language using the %s.',
		null,
		$HTML->ri_a('Github issue system', 'https://github.com/Xiphe/DropboxConflictMerger/issues')
	)
	->p('Or you grab %s, translate everything and create a git pull request or an issue to let '.
		'me know about your work.',
		null,
		$HTML->ri_a('this translation file', 'https://raw.github.com/Xiphe/DropboxConflictMerger/master/lang/EN.json')
	);