<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2017 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
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

// save original tables
LEPTON_handle::create_sik_table('mod_quickform');
LEPTON_handle::create_sik_table('mod_quickform_data');


//delete old template files
$file_names = array(
	'/modules/quickform/templates/de/contactform (HTML5).lte',
	'/modules/quickform/templates/de/mini_contactform (HTML5).lte',
	'/modules/quickform/templates/de/mini_contactform_semantic (HTML5).lte',
	'/modules/quickform/templates/en/callme_(HTML5).lte',
	'/modules/quickform/templates/en/contactform_(HTML5).lte',
	'/modules/quickform/templates/en/full_contactform_(HTML5).lte',
	'/modules/quickform/templates/en/mini_contactform_(HTML5).lte',
	'/modules/quickform/templates/nl/bel_me_terug (HTML5).lte',
	'/modules/quickform/templates/nl/contactformulier (HTML5).lte',
	'/modules/quickform/templates/nl/mini_contactformulier (HTML5).lte',
	'/modules/quickform/templates/nl/uitgebreid_contactformulier (HTML5).lte',
	'/modules/quickform/classes/class.qform.php'
);
LEPTON_handle::delete_obsolete_files($file_names);

//delete obsolete tables (since 3.1.0, wrong name)
LEPTON_handle::drop_table('xsik_quickform');
LEPTON_handle::drop_table('xsik_quickform_data');

?>