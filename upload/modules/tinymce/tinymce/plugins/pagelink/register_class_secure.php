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

$files_to_register = array(
	'pagelink.php'
);

LEPTON_secure::getInstance()->accessFiles( $files_to_register );

?>