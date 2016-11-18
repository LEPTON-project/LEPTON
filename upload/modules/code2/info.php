<?php

/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php 
 
$module_directory       = 'code2';
$module_name            = 'Code2';
$module_function        = 'page';
$module_version         = '2.3.2';
$module_platform        = '1.3';
$module_author          = 'Ryan Djurovich, Chio Maisriml, Thorn, Aldus.';
$module_license         = 'GNU General Public License';
$module_license_terms   = '-';
$module_description     = 'This module allows you to execute PHP, HTML, Javascript commands and internal comments (<span style="color:#FF0000;">limit access to users you can trust!</span>).';
$module_home            = ' https://www.lepton-cms.org';
$module_guid            = 'e5e36d7f-877a-4233-8dac-e1481c681c8d';

/**
 * see changelog on github
 * https://github.com/lepton-project/lepton
 */

?>