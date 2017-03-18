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
 * @copyright       2010-2017 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );

$basepath = "/backend/pages/";
$files_to_register = array(
	$basepath."add.php",
	$basepath."delete.php",
	$basepath."empty_trash.php",
	$basepath."index.php",
	$basepath."master_index.php",
	$basepath."modify.php",
	$basepath."move_down.php",
	$basepath."move_up.php",
	$basepath."overview.php",
	$basepath."restore.php",
	$basepath."save.php",
	$basepath."search.php",
	$basepath."sections.php",
	$basepath."sections_save.php",
	$basepath."settings.php",
	$basepath."settings2.php",
	$basepath."trash.php"
);

$lepton_filemanager->register( $files_to_register );

?>