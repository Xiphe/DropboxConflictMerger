<?php
$HTML->HTML5(null, 'lang=en')
	->x_title('Dropbox Conflict Merger')
	->viewport()
	->css('./res/css/bootstrap.css')
	->css('./res/css/bootstrap-responsive.css')
	->css('./res/css/diff.css')
	->css('./res/css/jquery.phpdiffmerge.min.css')
	->css('./res/css/style.css')
	->favicon('./res/img/favicon.ico')

->close('head')
->s_body()
	->s_div('.container-narrow|#main')
	->s_div('.masterhead');
	if (!isset($_GET['page']) || $_GET['page'] !== 'welcome') {
		$HTML->gs_ul('.nav nav-pills pull-right')
			->s_li()
				->x_a('Home', $HTML->baseUrl)
			->close('li')->s_li()
				->x_a('List Conflicts', '?page\=listConflicts')
			->close('li')->s_li()
				->x_a('About', '?page\=about')
			->close('li')->s_li()
				->x_a('Log out', '?action\=logout')
		->close('ul');
	}
		$HTML->x_h3('%s Dropbox Conflict Merger', '.muted', $HTML->ri_img('./res/img/favicon.ico'))
	->close('.masterhead')
	->x_p(
		'%sPlease ensure that the merge results contain the data you wanted '.
			'and give me some %s about how this is working for you. Thank you!',
		'.info',
		$HTML->rix_strong('This is a Beta release.').' ',
		$HTML->rix_a('feedback', 'https://github.com/Xiphe/DropboxConflictMerger/issues')
	)
	->hr()
	->s_div('.content')
;