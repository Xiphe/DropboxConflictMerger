<?php
namespace Xiphe\DropboxConflictMerger;
use Xiphe as X;

$files = $_GET['files'];
$path = $_GET['path'];

$HTML->s_div('.row-fluid')
	->h1('Resolve <span class="muted">%s</span>', null, $files[0]);

$diffFiles = Dropbox::getInstance()->initResolve($path, $files);
$HTML->p('Current Path: <code>%s</code>', '.muted', $path);

if (count($diffFiles) <= 1) {
	$HTML->h3('Done')
		->a('Back', '%?page\=listConflicts|.btn btn-large btn-success');
}
if (count($diffFiles) > 1):

$fileA = basename($diffFiles[0]['path']);
$fileB = basename($diffFiles[1]['path']);

$HTML->p(
		'Analyzing differences between <code>%s</code> and <code>%s</code>.',
		null,
		$fileA,
		$fileB
	);
if (count($files)) {
	$list = X\THETOOLS::readableList($files, '</code> and </code>', '</code>, </code>');

	$HTML->p('<em class="muted">%s</em> will be analyzed in the next step.', null, $list);
}
$HTML->hr();

if (Dropbox::getInstance()->justLineEndings()) {
	$HTML->p('Files are identical. Maybe the line-endings are different. <strong>Please choose the File you want to keep</strong>.');

	$i = 1;
	$HTML->s_form('method=post')->radio(
		array(
			'id' => 'keep'.$i++,
			'name' => 'keep',
			'value' => $diffFiles[0]['path'],
			'class' => 'pull-left'
		),
		array(
			'glue' => 'true',
			'inner' => $fileA,
			'pos' => 'after'
		),
		true
	)
	->radio(
		array(
			'id' => 'keep'.$i++,
			'name' => 'keep',
			'value' => $diffFiles[1]['path'],
			'class' => 'pull-left'
		),
		array(
			'glue' => 'true',
			'inner' => $fileB,
			'pos' => 'after'
		)
	)
	->hidden('name=action|value=resolveSimilar')
	->hidden('name=files[0]|value='.$HTML->esc($fileA))
	->hidden('name=files[1]|value='.$HTML->esc($fileB))
	// ->hidden('name=after|value=?'.$HTML->esc(http_build_query($_GET)))
	->submit('value=Continue|.btn btn-large pull-right');
} else {
	$HTML->end('.container-narrow')->s_div('.row-fluid|style=width: 90%; margin:auto;');
	Dropbox::getInstance()->getDiff();
	$HTML->end('.row-fluid')->s_div('.container-narrow');
	$HTML->a('Merge', '#merge|%#merge|.btn btn-large pull-right|style=margin-top: 10px;');
}
endif;