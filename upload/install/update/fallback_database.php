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

// set error level
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);

require_once('../../config.php'); 

echo '<h3>Current process : fallback to use class.database again</h3>';

$ini_file = LEPTON_PATH.'/framework/classes/setup.ini';
if ( !file_exists( $ini_file ) )
{
die("<br /><h3 style='color:red;'>File ".$ini_file." does not exists.</h3><h3 style='color:red;'>Please check your installation if fallback is possible.</h3>");
} else {
	$result = unlink ($ini_file);
	if (false === $result) {
		echo "Cannot delete file ".$ini_file.". Please check file permissions and ownership or delete file manually.";
		}
}

echo '<h5>Current process : recreate config.php</h5>'; 
$config_file = LEPTON_PATH.'/config.php';
$sik_file = LEPTON_PATH.'/config_sik.php';

// first delete config.php
if (file_exists($sik_file)) {
	$result = unlink ($config_file);
	if (false === $result) {
		echo "Cannot delete file ".$config_file.". Please check file permissions and ownership or delete file manually.";
	}
} else {
	die("<br /><h3 style='color:red;'>File ".$sik_file." does not exists.</h3><h3 style='color:red;'>Please check your installation if fallback is possible.</h3>");
}
// first copy config.php
if (!file_exists( $config_file ) )
{
	if (!copy($sik_file, $config_file)) {
		die ("failed to copy $sik_file...\n");
	}	
}

//delete config_sik
if (file_exists($config_file)) {
	$result = unlink ($sik_file);
		if (false === $result) {
			echo "Cannot delete file ".$sik_file.". Please check file permissions and ownership or delete file manually.";
		}
	}	

// at last: set db to current release-no
 echo '<h5>set database to special release</h5>';
$database->simple_query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'2.3.99\' WHERE `name` =\'lepton_version\'');


/**
 *  success message
 */
die("<h3>Fallback successfull!</h3><br />"); 

?>
