<?php
namespace Xiphe\DropboxConflictMerger;

$HTML->s_div('.row-fluid');

$allConflicts = Dropbox::getInstance()->getConflicts();
if (empty($allConflicts)) {
	$HTML->x_h1('Congratulations: No Conflicts found!');
	$HTML->x_p(
			'If you like to test the conflict merger you can download the %s. Just Extract them, put '.
			'the two files into your dropbox and reload this page.',
			null,
			$HTML->xri_a('example files', array(
				'href' => sprintf(
					'./src/Xiphe/DropboxConflictMerger/lang/examples_%s.zip',
					Translator::getInstance()->country
				)
			))
		);
} else {
	$HTML->x_h1('Conflicts');
	$HTML->gs_ul(".nav nav-list");
	foreach ($allConflicts as $type => $conflicts) {
		$HTML->li(ucfirst($type), '.nav-header');
		$e = explode('/', $type);
		if ($e[0] !== 'text') {
			$HTML->s_li()
				->x_span('%s This tool is designed and tested for Text files (.txt, .html, .js etc.). '.
					'You will most likely get unexpected results if you try to merge other filetypes. '.
					'This conflicts should be solved by hand.', '.warning', $HTML->rxi_strong('WARNING!'))
			->end();
		}
		foreach ($conflicts as $i => $conflict) {
			$path = dirname($conflict[0]['path']).'/';
			$name = $path.$conflict[0]['real_name'];

			$query = array(
				'page' => 'resolve',
				'path' => $path
			);
			$query['files'] = array($conflict[0]['real_name']);
			foreach ($conflict as $c) {
				$query['files'][] = basename($c['path']);
			}

			$HTML->s_li()
				->a(
					'%s%s',
					'%?'.$HTML->esc(http_build_query($query)),
					$HTML->ri_span(count($conflict)+1, '.number'),
					$name
				)
			->end('li');
		}
	}
}
