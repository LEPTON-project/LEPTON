<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2012, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2011-10-04
 * @version     $Id: update.php 1815 2012-03-23 08:28:22Z erpe $
 */

// set error level
// ini_set('display_errors', 1);
// error_reporting(E_ALL|E_STRICT);

require_once('../config.php');
global $admin;
if (!is_object($admin))
{
    require_once(WB_PATH . '/framework/class.admin.php');
    $admin = new admin('Addons', 'modules', false, false);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>LEPTON Update Script</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link href="http://lepton-cms.org/_packinstall/update.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="top">
  <div id="top-logo"></div>
  <div id="top-text">LEPTON update script</div>
</div>
<div id="update-script">
<?php
/**
 *  update LEPTON to 1.1.1 , check release
 */
if (version_compare(LEPTON_VERSION, "1.1.0", "<"))
{
    die("<h4>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 1.1.1</h3>';

//  database modifications above 1.1.0
$all = $database->query(" SELECT * from `" . TABLE_PREFIX . "addons` limit 1");
if ($all)
{
    $temp = $all->fetchRow(MYSQL_ASSOC);
    if (array_key_exists("php_version", $temp))
    {
        $database->query('ALTER TABLE `' . TABLE_PREFIX . 'addons` DROP COLUMN `php_version`, DROP COLUMN `sql_version`');
    }
}

// set new version number
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'1.1.1\' WHERE `name` =\'lepton_version\'');

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "form",
    "news",
    "initial_page",
    "tiny_mce_jq",
    "show_menu2",
    "wysiwyg_admin"
);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}


// delete obsolete module phplib
require_once(WB_PATH . '/framework/functions.php');
rm_full_dir(WB_PATH . '/modules/phplib');

echo '<h3>update to LEPTON 1.1.1 successfull!</h3><br /><hr /><br />';

/**
 *  update LEPTON to 1.1.2 , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.1.1", "<"))
{
    die("<h4>'>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 1.1.2</h3>';

//  database modifications above 1.1.1
$all = $database->query(" SELECT * from `" . TABLE_PREFIX . "users` limit 1");
if ($all)
{
    $temp = $all->fetchRow(MYSQL_ASSOC);
    if (array_key_exists("remember_key", $temp))
    {
        $database->query('ALTER TABLE `' . TABLE_PREFIX . 'users` DROP COLUMN `remember_key`');
    }
}

$all = $database->query(" DELETE from `" . TABLE_PREFIX . "settings` WHERE name = 'smart_login'");

/**
 *  database modifications
 */
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'1.1.2\' WHERE `name` =\'lepton_version\'');

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "news",
    "initial_page",
    "tiny_mce_jq",
    "addon_file_editor",
    "edit_area",
    "jsadmin",
    "menu_link",
    "output_interface",
    "pclzip",
    "show_menu2",
    "wrapper",
    "phpmailer",
    "wysiwyg_admin",
    "lib_jquery"
);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}

/**
 *  success message
 */
echo "<h3>update to LEPTON 1.1.2 successfull!</h3><br /><hr /><br />";


/**
 *  update LEPTON to 1.1.3 , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.1.2", "<"))
{
    die("<h4>'>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 1.1.3</h3>';

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "tiny_mce_jq",
    "news",
    "code2",
    "lib_jquery"
);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}

/**
 *  database modification
 */
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'1.1.3\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 1.1.3 successfull!</h3><br /><hr /><br />";


/**
 *  update LEPTON to 1.1.4 , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.1.3", "<"))
{
    die("<h4>'>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 1.1.4</h3>';

//delete leptoken debug file
$temp_path = WB_PATH."/framework/__debug_token.txt";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

/**
 *	try to remove obsolete column 'license_text'
 *  first check if the COLUMN `license_text` exists in the `addons` TABLE
 */
$checkDbTable = $database->query("SHOW COLUMNS FROM `".TABLE_PREFIX."addons` LIKE 'license_text'");
$column_exists = $checkDbTable->numRows() > 0 ? TRUE : FALSE;

if (true === $column_exists ) {
 $database->query('ALTER TABLE `' . TABLE_PREFIX . 'addons` DROP COLUMN `license_text`');
}


/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "tiny_mce_jq",
    "news",
    "form",
    "wrapper",
    "wysiwyg",
    "code2",
    "droplets",
    "captcha_control",
    "lib_jquery"
);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}

/**
 *  database modification
 */
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'1.1.4\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 1.1.4 successfull!</h3><br /><hr /><br />";


/**
 *  update LEPTON to 1.2.0 , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.1.4", "<"))
{
    die("<h4>'>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 1.2.0</h3>';

/**
 *  database modification
 */

//	try to remove obsolete columns from pages_table, first check if the columns exist
$checkDbTable = $database->query("SHOW COLUMNS FROM `".TABLE_PREFIX."pages` LIKE 'page_icon'");
$column_exists = $checkDbTable->numRows() > 0 ? TRUE : FALSE;

if (true === $column_exists ) {
 $database->query('ALTER TABLE `' . TABLE_PREFIX . 'pages` DROP COLUMN `page_icon`');
}

$checkDbTable = $database->query("SHOW COLUMNS FROM `".TABLE_PREFIX."pages` LIKE 'menu_icon_0'");
$column_exists = $checkDbTable->numRows() > 0 ? TRUE : FALSE;

if (true === $column_exists ) {
 $database->query('ALTER TABLE `' . TABLE_PREFIX . 'pages` DROP COLUMN `menu_icon_0`');
}

$checkDbTable = $database->query("SHOW COLUMNS FROM `".TABLE_PREFIX."pages` LIKE 'menu_icon_1'");
$column_exists = $checkDbTable->numRows() > 0 ? TRUE : FALSE;

if (true === $column_exists ) {
 $database->query('ALTER TABLE `' . TABLE_PREFIX . 'pages` DROP COLUMN `menu_icon_1`');
}
  // drop obsolete sik_news_tables for backword compatibility, new xsik_news_tables are created with upgrade.php of news module
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."sik_news_posts`");
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."sik_news_groups`");
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."sik_news_comments`");
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."sik_news_settings`");

// insert backend_title in settings table
$checkField = $database->query("SELECT * FROM ".TABLE_PREFIX."settings WHERE name = 'backend_title'");
$field_exists = $checkField->numRows() > 0 ? TRUE : FALSE;

if (true === $field_exists ) {
 echo "backend_title already exists, no new entry";
}
else {
$database->query("INSERT INTO ".TABLE_PREFIX."settings (name,value) VALUES ('backend_title', 'LEPTON-CMS')");
}

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "addon_file_editor",
    "captcha_control",
    "code2",
    "droplets",
    "form",
    "lib_jquery", 
    "menu_link",
    "news", 
    "phpmailer", 
    "show_menu2",              
    "tiny_mce_jq",
    "wrapper",
    "wysiwyg",    
    "wysiwyg_admin"    
);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
}


// at last: set db to current release-no
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'1.2.0\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 1.2.0 successfull!</h3>";


/**
 *  update LEPTON to 1.2.1 , check release
 */
$lepton_version = $database->get_one("SELECT `value` from `" . TABLE_PREFIX . "settings` where `name`='lepton_version'");
if (version_compare($lepton_version, "1.2.0", "<"))
{
    die("<h4>'>ERROR:UNABLE TO UPDATE, LEPTON Version : " . LEPTON_VERSION . " </h4>");
}
echo '<h3>Current process : updating to LEPTON 1.2.1</h3>';

/**
 *  database modification
 */

/**
 *  run upgrade.php of all modified modules
 *
 */
$upgrade_modules = array(
    "lib_jquery",               
    "tiny_mce_jq",
    "captcha_control",
    "form",
    "wysiwyg",    
    "output_interface",        
    "droplets"
);

foreach ($upgrade_modules as $module)
{
    $temp_path = WB_PATH . "/modules/" . $module . "/upgrade.php";

    if (file_exists($temp_path))
        require($temp_path);
} 

/**
 *  reload all addons
 */
if (file_exists('reload.php')) {
    include 'reload.php';
} 
// at last: set db to current release-no
$database->query('UPDATE `' . TABLE_PREFIX . 'settings` SET `value` =\'1.2.1\' WHERE `name` =\'lepton_version\'');

/**
 *  success message
 */
echo "<h3>update to LEPTON 1.2.1 successfull!</h3>"; 
echo "<br /><h3><a href=\"../admins/login/index.php\">please login and check update</></h3>";
?>
</div>
<div id="update-footer">
      <!-- Please note: the below reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
      <a href="http://wwww.lepton-cms.org" title="Lepton CMS">Lepton Core</a> is released under the
      <a href="http://www.gnu.org/licenses/gpl.html" title="Lepton Core is GPL">GNU General Public License</a>.
      <!-- Please note: the above reference to the GNU GPL should not be removed, as it provides a link for users to read about warranty, etc. -->
	    <br /><a href="http://wwww.lepton-cms.org" title="Lepton CMS">Lepton CMS Package</a> is released under several different licenses.
</div>
</body>
</html>