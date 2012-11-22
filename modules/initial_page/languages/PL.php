<?php

/**
 *
 * @module          initial_page
 * @author          Ralf Hertsch, Dietrich Roland Pehlke 
 * @copyright       2010-2011, Ralf Hertsch, Dietrich Roland Pehlke
 * @link            http://www.LEPTON-cms.org
 * @license         copyright, all rights reserved
 * @license_terms   please see info.php of this module
 * @version         $Id: PL.php 1447 2011-12-05 08:37:43Z erpe $
 *
 */
 
 // include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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




$MOD_INITIAL_PAGE = array(
	'label_user'	=> "Użytkownik",
	'label_page'	=> "Strona",
	'label_default'	=> "Domyślna strona startowa",
	'label_param'	=> "Parametry opcjonalne",
	'head_select'	=> "Proszę wybrać stronę użytkownika"
);

?>