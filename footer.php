<?php
$HTML->close('.content')->div(null, '.clearfix')
	->s_footer()
		->hr()
		->s_p()
			->span('© %s %s', null, date('Y'), $HTML->ri_a('Xiphe', 'http://xiphe.net'))
			->span(' &bull; %s', null, $HTML->ri_a('License', 'http://www.gnu.org/licenses/gpl-2.0.txt'))
			->s_a('https://github.com/Xiphe/DropboxConflictMerger')
				->img('style=position: absolute; top: 0; right: 0; border: 0;|src=https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png|alt=Fork me on GitHub')
	->close('.container-narrow')
	->jQuery()
	->js('./res/js/bootstrap.min.js')
	->js('./res/js/jquery.phpdiffmerge.0.1.min.js')
	->js('./res/js/script.js')
->close('all');