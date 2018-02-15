<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2018 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *
 */


$files_to_register = array(
	'add.php',
	'delete.php',
	'functions.php',
	'modify.php',
	'modify_template.php',
	'save.php',
	'backend_interface_js.php'	
	
);

LEPTON_secure::getInstance()->accessFiles( $files_to_register );

?>