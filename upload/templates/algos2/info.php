<?php

/**
 *  @template       Algos2 reworked  Backend-Theme
 *  @version        see info.php of this template
 *  @author         Jurgen Nijhuis & Ruud Eisinga, Dietrich Roland Pehlke, Bernd Michna, LEPTON project
 *	@copyright      2010-2018 Jurgen Nijhuis & Ruud Eisinga, Dietrich Roland Pehlke, Bernd Michna, LEPTON project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
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


// OBLIGATORY VARIABLES
$template_directory		= 'algos2';
$template_name			= 'Algos2 Theme';
$template_function		= 'theme';
$template_delete  		=  true;
$template_version		= '1.0.0';
$template_platform		= '4.x';
$template_author		= 'Jurgen Nijhuis, Dietrich Roland Pehlke, Bernd Michna, LEPTON project(last)';
$template_license		= '<a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>';
$template_license_terms	= '-';
$template_description	= 'Backend theme for LEPTON CMS';
$template_guid			= '60de3b94-3659-451c-a876-b9e17b49784b';
?>