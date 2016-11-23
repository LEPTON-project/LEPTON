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

echo '<h3>Current process : updating to LEPTON 2.4.0</h3>';

//check 
$ini_file = LEPTON_PATH.'/framework/classes/setup.ini';
if ( file_exists( $ini_file ) )
{
die("<br /><h3 style='color:red;'>File ".$ini_file." exists.</h3><h3 style='color:red;'>Please check your installation if update is needed.</h3>");

}

/**
 *  introduce new database-connector and setup.ini
 */
echo '<h5>Current process : ceate new config file and setup.ini</h5>'; 
// first copy config.php
$config_file = LEPTON_PATH.'/config.php';
$sik_file = LEPTON_PATH.'/config_sik.php';

if ( file_exists( $config_file ) )
{
	if (!copy($config_file, $sik_file)) {
		die ("failed to copy $config_file...\n");
	}	
}

// prepare config file
$config_content = "" .
"<?php\n".
"\n".
"if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! ".
"Forbidden call from \''.\$_SERVER['SCRIPT_NAME'].'\'!'); }\n\n".
"// new during update to LEPTON 2.4.0\n".
"\n".
"define('LEPTON_PATH', dirname(__FILE__));\n".
"define('LEPTON_URL', '".LEPTON_URL."');\n".
"define('ADMIN_PATH', LEPTON_PATH.'/admins');\n".
"define('ADMIN_URL', LEPTON_URL.'/admins');\n".
"\n".
"define('LEPTON_GUID', '".LEPTON_GUID."');\n".
"\n".
"define('WB_URL', LEPTON_URL);\n".
"define('WB_PATH', LEPTON_PATH);\n".
"\n".
"if (!defined('LEPTON_INSTALL')) require_once(LEPTON_PATH.'/framework/initialize.php');\n".
"\n".
"?>";

$config_filename = '../config.php';

// Check if the file exists and is writable first.
$config_filename = LEPTON_PATH.'/config.php';
if(($handle = fopen($config_filename, 'w')) === false) {
	set_error("Cannot open the configuration file ($config_filename)");
} else {
	if (fwrite($handle, $config_content, strlen($config_content) ) === false) {
		fclose($handle);
		set_error("Cannot write to the configuration file ($config_filename)");
	}
	// Close file
	fclose($handle);
}

/**
 *	Write the db setup.ini file
 */
$ini_filepath = "../framework/classes/setup.ini";
$s = ";
; This file is part of LEPTON Core, released under the GNU GPL
; Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
;
; NOTICE:LEPTON CMS Package has several different licenses.
; Please see the individual license in the header of each single file or info.php of modules and templates.
;
; @author          LEPTON Project
; @copyright       2010-2017 LEPTON Project
; @link            https://www.LEPTON-cms.org
; @license         http://www.gnu.org/licenses/gpl.html
; @license_terms   please see LICENSE and COPYING files in your package
;
;

; DB-setup for LEPTON-CMS\n

[database]
type = 'mysql'
host = '".DB_HOST."'
port = '".DB_PORT."'
user = '".DB_USERNAME."'
pass = '".DB_PASSWORD."'
name = '".DB_NAME."'
prefix = '".TABLE_PREFIX."'
";

$fp = fopen($ini_filepath, 'w');
if($fp) {
	fwrite( $fp , $s );
	fclose( $fp);
} else {
	set_error("Cannot open the setup file for the db!");
}

// Check if the user has entered a correct path
if(!file_exists(LEPTON_PATH.'/framework/class.admin.php')) {
	set_error('It seems that the absolute path you entered is incorrect');
}

echo "<h5>config file and setup.ini written successfully</h5>"; 


/**
 *  database modifications
 */
 echo '<h5>Current process : switch to new class database</h5>';  

$newfile = LEPTON_PATH.'/framework/classes/lepton_database.php';
if ( file_exists( $newfile ) )
{
	require_once( LEPTON_PATH."/framework/initialize.php" );	
}
echo "<h5>New class database: loaded</h5>"; 

/**
 *  install new modules
 *
 */
 echo '<h5>Current process : install new modules</h5>'; 
if (!function_exists('load_module')) require_once( LEPTON_PATH."/framework/summary.functions.php");

$install = array (
"/modules/lib_r_filemanager"
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
    "lib_twig",	
    "lib_semantic",	
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
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.4.0\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 2.4.0 successfull!</h3><br />"; 

?>
