<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2016 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

echo '<h3>Current process : updating to LEPTON 2.3.0</h3>';

/**
 *  database modifications
 */
echo '<h5>Current process : update passwords</h5>'; 
require_once(LEPTON_PATH.'/framework/functions/function.encrypt_password.php');	
$users =array();
$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."users` ",
		true,
		$users,
		true
	);
foreach ($users as $current) {
	$new_password= encrypt_password( $current['password'], LEPTON_GUID);
	$database->simple_query(
	"UPDATE `".TABLE_PREFIX."users` SET `password` = '".$new_password."' WHERE `user_id` = '".$current['user_id']."' "
	);
	
		if ( $database->is_error() ) {
			// Error updating database
			echo( $database->get_error());
		
		} else {							
		}	
}

echo '<h5>update passwords : successfull</h5>'; 

/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
    "initial_page",
    "lib_jquery",	
    "lib_lepton",
    "lib_search",
    "lib_semantic",	
    "quickform",	
    "tiny_mce_4"	
		

);

foreach ($upgrade_modules as $module)
{
    $temp_path = LEPTON_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}
echo "<h5>run upgrade.php of modified modules: successfull</h5>";


// at last: set db to current release-no
 echo '<h5>set database to new release</h5>';
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.3.0\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.3.0 successfull!</h3><br />"; 

?>
