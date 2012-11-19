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
	$i = 1;
	$HTML->end('.container-narrow')->s_div('.row-fluid|style=width: 90%; margin:auto;');
	Dropbox::getInstance()->getDiff();
	$HTML->end('.row-fluid')->s_div('.container-narrow')
	->a('Use All Left', '#left|%#left|.btn pull-left|style=margin-top: 10px;')
	->a('Use All Right', '#right|%#right|.btn pull-right|style=margin-top: 10px;')
	->div(null, '.clearfix')->hr()
	->h3('Save as:')
	->radio(
		array(
			'id' => 'keep'.$i++,
			'name' => 'keep',
			'value' => $diffFiles[0]['path'],
			'class' => 'pull-left filename',
			'checked' => null
		),
		array(
			'glue' => 'true',
			'inner' => $fileA,
			'pos' => 'after'
		)
	)
	->radio(
		array(
			'id' => 'keep'.$i++,
			'name' => 'keep',
			'value' => $diffFiles[1]['path'],
			'class' => 'pull-left filename'
		),
		array(
			'glue' => 'true',
			'inner' => $fileB,
			'pos' => 'after'
		)
	)
	->script('var currentFiles = %s;', null, json_encode(array($fileA, $fileB)))
	->a('Merge and next', '#merge|%#merge|.btn btn-large pull-right|style=margin-top: 10px;');
}
endif;