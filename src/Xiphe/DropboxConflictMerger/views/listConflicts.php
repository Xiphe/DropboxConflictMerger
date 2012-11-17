<?php
namespace Xiphe\DropboxConflictMerger;

$HTML->s_div('.row-fluid');

$allConflicts = Dropbox::getInstance()->getConflicts();
if (empty($allConflicts)) {
	$HTML->h1('Congratulations: No Conflicts found!');
} else {
	$HTML->h1('Conflicts');
	$HTML->gs_ul(".nav nav-list");
	foreach ($allConflicts as $type => $conflicts) {
		$HTML->li(ucfirst($type), '.nav-header');
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
					'<span class="number">%s</span>%s',
					'%?'.$HTML->esc(http_build_query($query)),
					count($conflict)+1,
					$name
				)
			->end('li');
		}
	}
}
