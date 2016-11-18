<?php

/**
 *	@module			wysiwyg Admin
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke
 * @copyright       2010-2017 Dietrich Roland Pehlke
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *	@platform		see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) {
		include($root.'/framework/class.secure.php');
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php  

$module_directory	= 'wysiwyg_admin';
$module_name		= 'wysiwyg Admin';
$module_function	= 'tool';
$module_version		= '2.1.4';
$module_platform	= '2.x';
$module_author		= 'Dietrich Roland Pehlke (Aldus)';
$module_license		= '<a href="http://www.gnu.org/licenses/lgpl.html" target="_blank">lgpl</a>';
$module_license_terms	= '-';
$module_description	= 'This module allows to manage some basic settings of the choosen wysiwyg-editor.';
$module_guid		= '895FD071-DA62-4E90-87C8-F3E11BC1F9AB';

?>