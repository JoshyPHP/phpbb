<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

if (php_sapi_name() != 'cli')
{
	die("This program must be run from the command line.\n");
}

//
// Security message:
//
// This script is potentially dangerous.
// Remove or comment the next line (die(".... ) to enable this script.
// Do NOT FORGET to either remove this script or disable it after you have used it.
//
//die("Please read the first lines of this script for instructions on how to enable it");

define('IN_PHPBB', true);
$phpbb_root_path = __DIR__ . '/../';
$phpEx = 'php';
include($phpbb_root_path . 'common.' . $phpEx);

// Force the same server settings as the test suite
$config = $phpbb_container->get('config');
$config['force_server_vars'] = true;
$config['server_protocol']   = 'http://';
$config['server_name']       = 'localhost';
$config['server_port']       = 80;
$config['script_path']       = '/phpbb';

// Create a default parser and renderer then save them as a bundle
$configurator = $phpbb_container->get('text_formatter.s9e.factory')->get_configurator();
$rendererGenerator = $configurator->rendering->setEngine('PHP');
$rendererGenerator->className = 'phpbb\\textformatter\\s9e\\default_bundle_renderer';
$rendererGenerator->filepath  = $phpbb_root_path . 'phpbb/textformatter/s9e/default_bundle_renderer.php';

// Copy this file's header
$file = file_get_contents(__FILE__);
if (!preg_match('(<\\?php\\s++\\K.*?\\*/\\s++)s', $file, $m))
{
	die("Could not find default header.\n");
}
$configurator->phpHeader = $m[0];

$configurator->saveBundle(
	'phpbb\\textformatter\\s9e\\default_bundle',
	$phpbb_root_path . 'phpbb/textformatter/s9e/default_bundle.php',
	['autoInclude' => false]
);
