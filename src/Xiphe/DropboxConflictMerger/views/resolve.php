<?php
namespace Xiphe\DropboxConflictMerger;
use Xiphe as X;

$files = $_GET['files'];
$path = $_GET['path'];

$HTML->s_div('.row-fluid')
	->x_h1('Resolve %s', null, $HTML->ri_span($files[0]), '.muted');

$diffFiles = Dropbox::getInstance()->initResolve($path, $files);
$HTML->x_p('Current Path: %s', '.muted', $HTML->ri_code($path));

if (count($diffFiles) <= 1) {
	$HTML->x_h3('Done')
		->x_a('Back', '%?page\=listConflicts|.btn btn-large btn-success');
}
if (count($diffFiles) > 1):

$fileA = basename($diffFiles[0]['path']);
$fileB = basename($diffFiles[1]['path']);

$HTML->x_p(
		'Analyzing differences between %s and %s.',
		null,
		$HTML->ri_code(
			$fileA,
			array(
				'id' => 'leftfile',
				'data-modified' => $diffFiles[0]['modified'],
				'data-client_mtime' => $diffFiles[0]['client_mtime']
			)
		),
		$HTML->ri_code(
			$fileB,
			array(
				'id' => 'rightfile',
				'data-modified' => $diffFiles[1]['modified'],
				'data-client_mtime' => $diffFiles[1]['client_mtime']
			)
		)
	);
if (count($files)) {
	$list = X\THETOOLS::readableList($files, '</code> and </code>', '</code>, </code>');

	$HTML->x_p(
		'%s will be analyzed in the next step.',
		null,
		$HTML->xri_em($list, '.muted')
	);
}
$HTML->hr();

if (Dropbox::getInstance()->justLineEndings()) {
	$HTML->x_p('Files are identical. Maybe the line-endings are different.');

	$i = 1;
	$HTML->s_form('method=post')

	->hidden(array(
		'name' => 'keep',
		'value' => $diffFiles[0]['path']
	))
	->hidden('name=action|value=resolveSimilar')
	->hidden('name=files[0]|value='.$HTML->esc($fileA))
	->hidden('name=files[1]|value='.$HTML->esc($fileB))
	// ->hidden('name=after|value=?'.$HTML->esc(http_build_query($_GET)))
	->submit(array(
		'value' => Translator::getInstance()->Language->get('Delete conflicted and Continue'),
		'class' => 'btn btn-large pull-right'
	));
} else {
	$i = 1;
	$HTML->end('.container-narrow')->s_div('.row-fluid|#thediff|style=width: 90%; margin:auto;');
	Dropbox::getInstance()->getDiff();
	$HTML->end('.row-fluid')->s_div('.container-narrow|#sub')->s_div('.content')
	->x_a('Use All Left', '#left|%#left|.btn pull-left|style=margin-top: 10px;')
	->x_a('Use All Right', '#right|%#right|.btn pull-right|style=margin-top: 10px;')
	->div(null, '.clearfix')->hr()
	->hidden(array(
		'name' => 'keep',
		'value' => $diffFiles[0]['path']
	))
	// ->x_h3('Save as:')
	// ->radio(
	// 	array(
	// 		'id' => 'keep'.$i++,
	// 		'name' => 'keep',
	// 		'value' => $diffFiles[0]['path'],
	// 		'class' => 'pull-left filename',
	// 		'checked' => null
	// 	),
	// 	array(
	// 		'glue' => 'true',
	// 		'inner' => $fileA,
	// 		'pos' => 'after'
	// 	)
	// )
	// ->radio(
	// 	array(
	// 		'id' => 'keep'.$i++,
	// 		'name' => 'keep',
	// 		'value' => $diffFiles[1]['path'],
	// 		'class' => 'pull-left filename'
	// 	),
	// 	array(
	// 		'glue' => 'true',
	// 		'inner' => $fileB,
	// 		'pos' => 'after'
	// 	)
	// )
	->script('var currentFiles = %s;', null, json_encode(array($fileA, $fileB)))
	->x_a('Merge and next', '#merge|%#merge|.btn btn-large pull-right|style=margin-top: 10px;')
	->img('src=./res/img/loadinfo.net.gif|alt=loading|class=pull-right|#loader');
}
endif;