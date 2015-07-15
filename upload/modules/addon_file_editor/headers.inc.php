<?php

/**
 *
 * @module		Addon File Editor
 * @author		LEPTON Project
 * @copyright	2010-2015 LEPTON Project
 * @license     GNU General Public License
 * @version		see info.php
 * @platform	see info.php
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




$mod_headers = array();

if ( DEFAULT_THEME =='lepsem' ) {
    $mod_headers = array(
		'backend' => array(
		    'css' => array(
				array(
					'media'		=> 'screen',
					'file'		=> '/templates/algos/theme.css',
				)
			),

		),
	);
}

?>