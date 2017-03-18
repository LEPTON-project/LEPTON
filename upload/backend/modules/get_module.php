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

$m_id = intval( $_POST['id'] );

$module = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."addons` WHERE `addon_id`=".$m_id,
	true,
	$module,
	false
);

/**
 *	Get the "delete" information from the info.php from the module.
 */
$look_up_filename = LEPTON_PATH."/modules/".$module['directory']."/info.php";
if (true === file_exists($look_up_filename)) {
	require( $look_up_filename );
	if (isset($module_delete)) {
		$module['module_delete'] = (bool) $module_delete;
	} else {
		$module['module_delete'] = true;
	}
		
} else {
	$module['module_delete'] = true;
}

echo json_encode( $module );
?>