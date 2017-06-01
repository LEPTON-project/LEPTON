<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 */

 /* only for direct test purposes 
 require_once('../../../config.php');
global $admin;
if (!is_object($admin))
{
    require_once(LEPTON_PATH . '/framework/class.admin.php');
    $admin = new admin('Addons', 'modules', false, false);
}
 */
// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

echo ('<h3>Current process : updating to LEPTON 3.1.0</h3>');

echo ('<h5>Current process : rename the ini-file</h5>'); 

$temp_lookup = LEPTON_PATH."/framework/classes/setup.ini";
if( true == file_exists( $temp_lookup )) {
	$s = file_get_contents( dirname(__FILE__)."/setup.ini" );
	$fp = fopen( dirname(__FILE__)."/setup.ini", 'w');
	fwrite($fp, "; <?php die(); ?>\n".$s);
	fclose($fp);
	rename($temp_lookup, $temp_lookup.".php");	
	echo "<h5>rename ini: successfull</h5>"; 
} else {
	echo "<h5>rename ini not needed.</h5>"; 
}

echo ('<h5>Current process : delete unneeded files</h5>'); 
$file = array (
"/config_sik.php",
"/framework/class.database.php",
"/framework/class.wbmailer.php"
);
 
foreach ($file as $del)
{
    $temp_path = LEPTON_PATH . $del;
    if (file_exists($temp_path)) 
	{
		$result = unlink ($temp_path);
		if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
		}
	}
}	
echo "<h5>Delete files: successfull</h5>"; 

/**
 *  run upgrade.php of all modified modules
 *
 */
 echo '<h5>Current process : run modules upgrade.php</h5>';  
$upgrade_modules = array(
    "lib_phpmailer",
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
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'3.1.0\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 3.1.0 successfull!</h3><br />"; 

?>
