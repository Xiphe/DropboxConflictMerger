<?php
/*
 * Prepare global variables.
 */
global $HTML;

/* Xiphes basics (HTML, THEDEBUG, THETOOLS) */
include 'src/Xiphe/autoload.php';
include 'src/Xiphe/globaldebug.php';
$HTML = new Xiphe\HTML('./');

/* Eden */
include 'src/eden-3.1.php';
Xiphe\DropboxConflictMerger\Translator::getInstance();

/* PEAR Dropbox PHP Api */
include 'Dropbox/autoload.php';
/* PHP Diff */ 
include 'src/php-diff/autoload.php';

/* 
 * Uncomment the following line for benchmarking the run time of the script
 */
// Xiphe\DropboxConflictMerger\Base::benchmark();

/*
 * Include the configuration file.
 */
if (file_exists('config.php')) {
	include 'config.php';
} else {
	include 'header.php';
	$HTML->h2('Error: Configuration file not found!')
	->p('There should be a file called config.php in the root folder. Check if there is a config-sample.php and rename it to config.php.');
	include 'footer.php';
	die();
}

/*
 * Check if all required configuration keys are set.
 */
if (
	($error = Xiphe\THETOOLS::getRequiredArgsError(
		$config,
		array(
			'consumerKey',
			'consumerSecret',
			'baseUrl',
			'encryptionKey'
		)
	)) !== false
) {
	include 'header.php';
	$HTML->h2('Error: Configuration not valid!')
	->p($error);
	include 'footer.php';
	die();
}

/*
 * Pass the BaseUrl to HTML.
 */
$HTML->setOption('baseUrl', $config['baseUrl']);

/*
 * Initiate Dropbox.
 */
Xiphe\DropboxConflictMerger\Dropbox::getInstance($config);

/*
 * Prepare visuals.
 */
$Layout = Xiphe\DropboxConflictMerger\Layout::getInstance();

