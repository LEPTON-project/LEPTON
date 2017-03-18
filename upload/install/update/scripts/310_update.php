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
 * @link            https://www.LEPTON-cms.org
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

echo ('<h3>Current process : updating to LEPTON 3.0.0</h3>');

echo ('<h5>Current process : rename database fields</h5>'); 
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `name` =\'mailer_routine\' WHERE `name` =\'wbmailer_routine\'');
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `name` =\'mailer_default_sendername\' WHERE `name` =\'wbmailer_default_sendername\'');
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `name` =\'mailer_smtp_host\' WHERE `name` =\'wbmailer_smtp_host\'');
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `name` =\'mailer_smtp_auth\' WHERE `name` =\'wbmailer_smtp_auth\'');
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `name` =\'mailer_smtp_username\' WHERE `name` =\'wbmailer_smtp_username\'');
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `name` =\'mailer_smtp_password\' WHERE `name` =\'wbmailer_smtp_password\'');
die ('<h5>Rename database fields: successfull</h5>'); 

echo ('<h5>Current process : delete unneeded files</h5>'); 
// KEEP IN MIND TO REWORK initialize.php

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

// introduce phpmailer release 6.x
echo '<h5>Current process : replace old phpmailer</h5>';
//first delete
$to_delete = array (
"/modules/lib_phpmailer",
);
 
foreach ($to_delete as $del)
{
    $temp_path = LEPTON_PATH . $del;


    if (file_exists($temp_path)) 
		{
    	rm_full_dir( LEPTON_PATH . $del );
		} else {
				echo ("<h4 style='color:green;text-align:center;font-size:20px;'> directory $del not exists</h4>");
				}
}

//then move new addon
$to_move = array (
"/modules/lib_phpmailer"
);

	// move new used directories
foreach ($to_move as $file)
{
    $temp_path = LEPTON_PATH .'/install/L3U'. $file ;

	rename( $temp_path,dirname(__FILE__).$file);
	
}	
		
echo "<h5>replace old phpmailer: successfull</h5>";

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
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'3.0.0\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
echo "<h3>update to LEPTON 3.0.0 successfull!</h3><br />"; 

?>
