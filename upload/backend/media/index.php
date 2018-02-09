<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

// enable custom files
LEPTON_handle::require_alternative('/templates/'.DEFAULT_THEME.'/backend/backend/media/index.php');

// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();

LEPTON_handle::include_files("/modules/lib_r_filemanager/library.php");	

$page_values = array(
	'source'	=> 	LEPTON_URL.'/modules/lib_r_filemanager/filemanager/dialog.php?akey='.$_SESSION['rfkey'],
	'image'		=> 	LEPTON_URL.'/modules/lib_r_filemanager/filemanager/img/blank.png',	
	'id'		=>	"id='r_filemanager'",	
	'seamless'	=>	"seamless='seamless'"
);

$oTWIG->registerPath( THEME_PATH."theme","media" );
echo $oTWIG->render(
	"@theme/media.lte",
	$page_values
);
 
$admin->print_footer();
?>