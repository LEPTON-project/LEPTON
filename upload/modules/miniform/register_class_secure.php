<?php

/**
 *
 *	@module			miniform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2015 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *
 */

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );


$files_to_register = array(
	'/modules/miniform/add.php',
	'/modules/miniform/delete.php',
	'/modules/miniform/functions.php',
	'/modules/miniform/modify.php',
	'/modules/miniform/modify_template.php',
	'/modules/miniform/save.php'	
	
);

$lepton_filemanager->register( $files_to_register );

?>