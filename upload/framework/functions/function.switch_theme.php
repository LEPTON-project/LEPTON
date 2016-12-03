<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		switch_theme
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

 /**
 *	This file is included if the backend-theme setting has been modified
 *
 *	see admins/settings/save.php  around line 202
 *	or
 *	backend/settings/save.php  around line 202
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

function switch_theme( $sThemeName ) {
	global $database;
	
	if ($sThemeName != 'algos') {
		
		/**
		 *	All other (newer) themes used the "backend" directory.
		 *	Only 'Algos' will use the "old" admins-folder!
		 */
		 
		 // copy config file
		$file = LEPTON_PATH.'/config.php';
		$newfile = LEPTON_PATH.'/config_sik.php';

		if (!copy($file, $newfile)) {
			die ("<div class='ui negative message'>failed to copy $file...\n</div>");
		}
		
		// prepare new config file
		$config_content = "" .
		"<?php\n".
		"\n".
		"if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! ".
		"Forbidden call from \''.\$_SERVER['SCRIPT_NAME'].'\'!'); }\n\n".
		"\n\n// (switched backend-theme to lepsem) config file created by ".CORE." ".VERSION."\n".
		"define('LEPTON_PATH', dirname(__FILE__));\n".
		"define('LEPTON_URL', '".LEPTON_URL."');\n".
		"define('ADMIN_PATH', LEPTON_PATH.'/backend');\n".
		"define('ADMIN_URL', LEPTON_URL.'/backend');\n".
		"\n".
		"define('LEPTON_GUID', '".LEPTON_GUID."');\n".
		"define('WB_URL', LEPTON_URL);\n".
		"define('WB_PATH', LEPTON_PATH);\n".
		"\n".
		"if (!defined('LEPTON_INSTALL')) require_once(LEPTON_PATH.'/framework/initialize.php');\n".
		"\n".
		"?>";

		// Check if the file exists and is writable first.
		$config_filename = LEPTON_PATH.'/config.php';
		if(($handle = fopen($config_filename, 'w')) === false) {
			die("Cannot open the configuration file ($config_filename)");
		} else {
			if (fwrite($handle, $config_content, strlen($config_content) ) === false) {
				fclose($handle);
				die("Cannot write to the configuration file ($config_filename)");
			}
			// Close file
			fclose($handle);
		}
		
		// set theme to lepsem
		$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'lepsem\' WHERE `name` =\'default_theme\'');

	} else {
		/**
		 *	Current theme is "Algos"!
		 *	Only algos is used the "old" admins-directory!
		 */
		// copy config file
		$file = LEPTON_PATH.'/config.php';
		$newfile = LEPTON_PATH.'/config_sik.php';

		if (!copy($file, $newfile)) {
			die ("<div class='ui negative message'>failed to copy $file...\n</div>");
		}
		
		// prepare new config file
		$config_content = "" .
		"<?php\n".
		"\n".
		"if(defined('LEPTON_PATH')) { die('By security reasons it is not permitted to load \'config.php\' twice!! ".
		"Forbidden call from \''.\$_SERVER['SCRIPT_NAME'].'\'!'); }\n\n".
		"\n\n// (switched backend-theme to algos) config file created by ".CORE." ".VERSION."\n".
		"define('LEPTON_PATH', dirname(__FILE__));\n".
		"define('LEPTON_URL', '".LEPTON_URL."');\n".
		"define('ADMIN_PATH', LEPTON_PATH.'/admins');\n".
		"define('ADMIN_URL', LEPTON_URL.'/admins');\n".
		"\n".
		"define('LEPTON_GUID', '".LEPTON_GUID."');\n".
		"define('WB_URL', LEPTON_URL);\n".
		"define('WB_PATH', LEPTON_PATH);\n".
		"\n".
		"if (!defined('LEPTON_INSTALL')) require_once(LEPTON_PATH.'/framework/initialize.php');\n".
		"\n".
		"?>";

		// Check if the file exists and is writable first.
		$config_filename = LEPTON_PATH.'/config.php';
		if(($handle = fopen($config_filename, 'w')) === false) {
			die("Cannot open the configuration file ($config_filename)");
		} else {
			if (fwrite($handle, $config_content, strlen($config_content) ) === false) {
				fclose($handle);
				die("Cannot write to the configuration file ($config_filename)");
			}
			// Close file
			fclose($handle);
		}
		
		// set theme back to algos
		$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'algos\' WHERE `name` =\'default_theme\'');

	}
}
?>