<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released special license.
 * License can be seen in the info.php of this module.
 *
 * @module          lib Responsive Filemanager
 * @author          LEPTON Project, Alberto Peripolli (http://responsivefilemanager.com/)
 * @copyright       2016-2018 LEPTON Project, Alberto Peripolli
 * @link            https://lepton-cms.org
 * @license         please see info.php of this module
 * @license_terms   please see info.php of this module
 *
 */


$files_to_register = array(
	'config/config.php',
	'dialog.php',
	'upload.php',
	'force_download.php',
	'execute.php',
	'ajax_calls.php'
);

LEPTON_secure::getInstance()->accessFiles( $files_to_register );

?>