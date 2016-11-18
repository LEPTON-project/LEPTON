<?php

/**
 *  @module         pagelin-plugin for TinyMCE
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../../../../framework/class.lepton.filemanager.php" );

$files_to_register = array(
	'/modules/tiny_mce_4/tiny_mce/plugins/pagelink/pagelink.php'
);

$lepton_filemanager->register( $files_to_register );

?>