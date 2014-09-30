<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php




// create the new dropleps table
	$table = TABLE_PREFIX .'mod_dropleps';
  $database->query("DROP TABLE IF EXISTS `$table`");  
	$database->query("CREATE TABLE `$table` (
		`id` INT NOT NULL auto_increment,
		`name` VARCHAR(32) NOT NULL,
		`code` LONGTEXT NOT NULL ,
		`description` TEXT NOT NULL,
		`modified_when` INT NOT NULL default '0',
		`modified_by` INT NOT NULL default '0',
		`active` INT NOT NULL default '0',
		`admin_edit` INT NOT NULL default '0',
		`admin_view` INT NOT NULL default '0',
		`show_wysiwyg` INT NOT NULL default '0',
		`comments` TEXT NOT NULL,
		PRIMARY KEY ( `id` )
		)"
	);
	// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
}



// create the new permissions table
$table = TABLE_PREFIX .'mod_dropleps_permissions';
$database->query("DROP TABLE IF EXISTS `$table`");
$database->query("CREATE TABLE `$table` (
	`id` INT(10) UNSIGNED NOT NULL,
	`edit_groups` VARCHAR(50) NOT NULL,
	`view_groups` VARCHAR(50) NOT NULL,
	PRIMARY KEY ( `id` )
	)"
);

	// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
}

// create the settings table
$table = TABLE_PREFIX .'mod_dropleps_settings';
$database->query("DROP TABLE IF EXISTS `$table`");
$database->query("CREATE TABLE `$table` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`attribute` VARCHAR(50) NOT NULL DEFAULT '0',
	`value` VARCHAR(50) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	)"
);

	// check for errors
if ($database->is_error()) {
 echo $datbase->get_error();
}

// insert settings
$database->query("INSERT INTO `".TABLE_PREFIX ."mod_dropleps_settings` (`id`, `attribute`, `value`) VALUES
(1, 'Manage backups', '1'),
(2, 'Import dropleps', '1'),
(3, 'Delete dropleps', '1'),
(4, 'Add dropleps', '1'),
(5, 'Export dropleps', '1'),
(6, 'Modify dropleps', '1'),
(7, 'Manage perms', '1');
");


// import default dropleps
if (!function_exists('droplep_install')) {
    include_once LEPTON_PATH.'/modules/dropleps/functions.php';
}
if (file_exists(dirname(__FILE__) . '/install/droplep_year.zip')) {
droplep_install(dirname(__FILE__) . '/install/droplep_check-css.zip', LEPTON_PATH . '/temp/unzip/');
droplep_install(dirname(__FILE__) . '/install/droplep_EditThisPage.zip', LEPTON_PATH . '/temp/unzip/');
droplep_install(dirname(__FILE__) . '/install/droplep_EmailFilter.zip', LEPTON_PATH . '/temp/unzip/');
droplep_install(dirname(__FILE__) . '/install/droplep_LoginBox.zip', LEPTON_PATH . '/temp/unzip/');
droplep_install(dirname(__FILE__) . '/install/droplep_Lorem.zip', LEPTON_PATH . '/temp/unzip/');
droplep_install(dirname(__FILE__) . '/install/droplep_year.zip', LEPTON_PATH . '/temp/unzip/');
}

?>