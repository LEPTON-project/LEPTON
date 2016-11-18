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

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );


$files_to_register = array(
	'/modules/quickform/add.php',
	'/modules/quickform/delete.php',
	'/modules/quickform/functions.php',
	'/modules/quickform/modify.php',
	'/modules/quickform/modify_template.php',
	'/modules/quickform/save.php'	
	
);

$lepton_filemanager->register( $files_to_register );

?>