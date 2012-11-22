<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 4.4.9 and higher
 * @version         $Id$
 * @filesource		$HeadURL$
 * @lastmodified    $Date$
 *
 */

if(!defined('WB_PATH')) die(header('Location: ../../index.php'));

$table = TABLE_PREFIX .'mod_droplets';

$info = $database->query("SELECT * from `$table` limit 0,1" );
$fields = $info->fetchRow();
if (!array_key_exists("admin_edit", $fields)) {
	/**
	 *	Call from the upgrade-script
	 */
	
	if (function_exists('db_add_field')) {
		db_add_field("admin_edit", 'mod_droplets', "INT NOT NULL default '0'");
		db_add_field("admin_view", 'mod_droplets', "INT NOT NULL default '0'");
		db_add_field("show_wysiwyg", 'mod_droplets', "INT NOT NULL default '0'");
	} else {
		/**
		 * Not call by the upgrade-script
		 */
		$database->query("ALTER TABLE `$table` (
			`admin_edit` INT NOT NULL default '0',
			`admin_view` INT NOT NULL default '0',
			`show_wysiwyg` INT NOT NULL default '0'
			)");
	}
}

// import and installl additional droplets zip
if (file_exists(WB_PATH . '/modules/droplets/example/droplet_check-css.zip')) {
include_once (WB_PATH . '/modules/droplets/functions.inc.php');
wb_unpack_and_import(WB_PATH . '/modules/droplets/example/droplet_check-css.zip', WB_PATH . '/temp/unzip/');
}

// import and installl additional droplets zip
if (file_exists(WB_PATH . '/modules/droplets/example/droplet_year.zip')) {
include_once (WB_PATH . '/modules/droplets/functions.inc.php');
wb_unpack_and_import(WB_PATH . '/modules/droplets/example/droplet_year.zip', WB_PATH . '/temp/unzip/');
}

// delete additional droplets zip after installation
$temp_path = WB_PATH."/modules/droplets/example/droplet_check-css.zip";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

$temp_path = WB_PATH."/modules/droplets/example/droplet_year.zip";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
?>