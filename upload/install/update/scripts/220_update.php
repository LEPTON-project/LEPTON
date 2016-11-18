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

echo '<h3>Current process : updating to LEPTON 2.2.0</h3>';

/**
 *  database modifications
 */
 echo '<h5>Current process : database modifications</h5>';  
// set unique keys in users and groups table to prevent double entries via insert
// ALTER TABLE and prevent double modification
$unique_goups = array();
$database->execute_query(
"SHOW INDEX FROM ".TABLE_PREFIX."groups WHERE Column_name = 'name'",
TRUE,
$unique_goups, FALSE
);
if (count ($unique_goups) == 0) {
 $database->execute_query("ALTER TABLE ".TABLE_PREFIX."groups ADD UNIQUE (`name`) ");
 echo('<h5>name in groups set to unique</h5>');
}

// ALTER email from text to varchar
 $database->execute_query("ALTER TABLE ".TABLE_PREFIX."users MODIFY COLUMN `email` VARCHAR( 128 ) NOT NULL ");
 
 // ALTER TABLE and prevent double modification
$unique_email = array();
$database->execute_query(
"SHOW INDEX FROM ".TABLE_PREFIX."users WHERE Column_name = 'email'",
TRUE,
$unique_email, FALSE
);
if (count ($unique_email) == 0) {
 $database->execute_query("ALTER TABLE ".TABLE_PREFIX."users ADD UNIQUE (`email`) ");
  echo('<h5>email in users set to unique </h5>');
}
 
 // ALTER TABLE and prevent double modification
$unique_user = array();
$database->execute_query(
"SHOW INDEX FROM ".TABLE_PREFIX."users WHERE Column_name = 'username'",
TRUE,
$unique_user, FALSE
);
if (count ($unique_user) == 0) {
 $database->execute_query("ALTER TABLE ".TABLE_PREFIX."users ADD UNIQUE (`username`) ");
   echo('<h5>username in users set to unique</h5>');
}

// delete field intro_page
$database->simple_query("DELETE FROM `".TABLE_PREFIX."settings` WHERE `name` ='intro_page'");

echo "<h5>database modifications: successfull</h5>"; 

/**
 *  delete not needed files
 */
echo '<h5>Current process : delete some files</h5>';  
$temp_path = LEPTON_PATH."/account/details.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

$temp_path = LEPTON_PATH."/account/email.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

$temp_path = LEPTON_PATH."/account/password.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
//delete intro page if exists, function not needed!
$temp_path = LEPTON_PATH.PAGES_DIRECTORY."/intro.php";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
 
echo "<h5>delete files: successfull</h5>"; 

/**
 *  install new modules
 *
 */
 echo '<h5>Current process : install new modules</h5>'; 
if (!function_exists('load_module')) require_once( LEPTON_PATH."/framework/summary.functions.php");

$install = array (
"/modules/quickform"
);

// install new modules
foreach ($install as $module)
{
    $temp_path = LEPTON_PATH . $module ;

require ($temp_path.'/info.php');
load_module( $temp_path, true );

foreach(
array(
'module_license', 'module_author'  , 'module_name', 'module_directory',
'module_version', 'module_function', 'module_description',
'module_platform', 'module_guid'
) as $varname )
{
if (isset(  ${$varname} ) ) unset( ${$varname} );
}
}
echo "<h5>install new modules: successfull</h5>"; 
 
/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
    "droplets",
    "captcha_control",	
    "initial_page",		
    "lib_jquery",	
    "lib_lepton",	
    "lib_phpmailer",	
    "lib_search",	
    "lib_semantic",
    "lib_twig",
    "news",
    "show_menu2",		
	"tiny_mce_4",
	"wysiwyg_admin",	
    "wysiwyg"	
		

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
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.2.0\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.2.0 successfull!</h3><br />"; 

?>
