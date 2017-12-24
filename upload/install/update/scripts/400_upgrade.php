<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

/* only for direct test purposes 
require_once('../../../config/config.php');
global $admin;
$admin = new LEPTON_admin('Addons', 'modules', false, false);
 */
 die('angekommen');
// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);
 
echo ('<h3>Current process : updating to LEPTON 3.1.0</h3>');

echo ('<h5>Current process : move config amd ini file to new location</h5>'); 

if(file_exists ('../config.php')) {
copy ('../framework/classes/setup.ini.php', '../config/lepton.ini.php');
copy ('../config.php', '../config/config.php');
}
// modify LEPTON_PATH
$old_string = file_get_contents ('../config/config.php');
$new_string = str_replace ("define('LEPTON_PATH', dirname(__FILE__));","define('LEPTON_PATH', dirname(dirname(__FILE__)));",$old_string);
file_put_contents('../config/config.php',$new_string);

echo "<h5>Move files: successfull</h5>"; 

echo ('<h5>Current process : delete unneeded files</h5>'); 

$file_names = array (
    "/framework/class.admin.php",
    "/framework/class.admin_phplib.php",
    "/framework/class.admin_twig.php",
    "/framework/class.frontend.php",
    "/framework/class.login.php",
    "/framework/class.order.php",
    "/framework/class.securecms.php",
    "/framework/class.validate.request.php",
    "/framework/var.date_formats.php",
    "/framework/var.time_formats.php",
    "/framework/var.timezones.php",
    "/framework/class.wb.php"
);
LEPTON_handle::delete_obsolete_files($file_names);

echo "<h5>Delete files: successfull</h5>"; 

/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
 
$module_names = array(
    "captcha_control",
    "code2",
    "droplets",
    "initial_page",	
    "lib_jquery",	
    "lib_lepton",	
    "lib_phpmailer",
    "lib_r_filemanager",	
    "lib_search",	
    "lib_twig",	
	"menu_link",
    "news",
    "quickform",	
    "show_menu2",	
    "tinymce",
    "wrapper",	
	"wysiwyg"
);
LEPTON_handle::upgrade_modules($module_names);

echo "<h5>run upgrade.php of modified modules: successfull</h5>";


// at last: set db to current release-no
echo '<h5>set database to new release</h5>';
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'3.1.0\' WHERE `name` =\'lepton_version\'');

$file_names = array (
'/framework/classes/setup.ini.php',
'/config.php',
'/config.sik.php'
);
LEPTON_handle::delete_obsolete_files($file_names);

/**
 *  success message
 */
echo "<h3>update to LEPTON 3.1.0 successfull!</h3><br />"; 

?>