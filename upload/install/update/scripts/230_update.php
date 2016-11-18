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
 * @link            https://www.LEPTON-cms.org
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
echo '<h5>Current process : add table</h5>';  
$table = TABLE_PREFIX."temp";

$database->query("CREATE TABLE IF NOT EXISTS `".$table."`  (
	`temp_id` INT( 2 ) NOT NULL auto_increment,
	`temp_browser` varchar(64) NOT NULL DEFAULT \"\",
	`temp_ip` varchar(64) NOT NULL DEFAULT \"\",
	`temp_time` int(24) NOT NULL DEFAULT \"0\",
	`temp_count` int(2) NOT NULL DEFAULT \"0\",
	`temp_active` tinyint(1) NOT NULL DEFAULT \"0\",		
	PRIMARY KEY (`temp_id`)
)"
);
// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
 die('add table: NOT successfull');
}	
echo "<h5>add table: successfull</h5>";

/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
    "droplets",
    "initial_page",	
    "lib_jquery",	
    "lib_lepton",
    "lib_search",	
    "lib_lepton",	
    "lib_semantic",	
    "news",
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
