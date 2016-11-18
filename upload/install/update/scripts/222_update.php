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

echo '<h3>Current process : updating to LEPTON 2.2.2</h3>';

/**
 *  database modifications
 */

 // delete obsolete directories
require_once(LEPTON_PATH . '/framework/summary.functions.php');
rm_full_dir(ADMIN_PATH . '/service');
rm_full_dir(ADMIN_PATH . '/support');
 
/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
	"code2",	
    "droplets",	
    "lib_jquery",
    "lib_lepton",
    "lib_phpmailer",
    "lib_semantic",	
    "lib_twig",	
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
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.2.2\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.2.2 successfull!</h3><br />"; 

?>
