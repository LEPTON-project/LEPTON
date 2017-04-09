<?php

/**
 *
 * @module          initial_page
 * @author          LEPTON project 
 * @copyright       2010-2017 LEPTON project 
 * @link            https://lepton-cms.org
 * @license         copyright, all rights reserved
 * @license_terms   please see info.php of this module
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




$MOD_INITIAL_PAGE = array(
	'label_user'	=> "Gebruiker",
	'label_page'	=> "Pagina",
	'label_default'	=> "Standaard startpagina",
	'label_param'	=> "Optionele parameters",
	'head_select'	=> "Select een startpagina voor de gebruiker",
	'save'	=> "Opslaan"
);

?>