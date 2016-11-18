<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released special license.
 * License can be seen in the info.php of this module.
 *
 * @module          lib Responsive Filemanager
 * @author          LEPTON Project, Alberto Peripolli (http://responsivefilemanager.com/)
 * @copyright       2016-2017 LEPTON Project, Alberto Peripolli
 * @link            https://www.LEPTON-cms.org
 * @license         please see info.php of this module
 * @license_terms   please see info.php of this module
 *
 */

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../../framework/class.lepton.filemanager.php" );

$path = '/modules/lib_r_filemanager/filemanager';

$files_to_register = array(
	$path.'/config/config.php',
	$path.'/dialog.php',
	$path.'/upload.php',
	$path.'/force_download.php',
	$path.'/execute.php',
	$path.'/ajax_calls.php'
);

$lepton_filemanager->register( $files_to_register );

?>