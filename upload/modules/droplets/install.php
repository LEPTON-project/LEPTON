<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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

//	Collect all errors during the installation to display them all.
$droplets_install_errors = array();

//	create the droplets table
$table = TABLE_PREFIX .'mod_droplets'; 
$database->simple_query("CREATE TABLE IF NOT EXISTS `".$table."`  (
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
if ($database->is_error())
{
	$droplets_install_errors[] = $database->get_error();
}

// create the new permissions table
$table = TABLE_PREFIX .'mod_droplets_permissions';
$database->simple_query("CREATE TABLE IF NOT EXISTS `".$table."` (
	`id` INT(10) UNSIGNED NOT NULL,
	`edit_perm` VARCHAR(50) NOT NULL,
	`view_perm` VARCHAR(50) NOT NULL,
	PRIMARY KEY ( `id` )
	)"
);

// check for errors
if ($database->is_error())
{
	$droplets_install_errors[] = $database->get_error();
}

// create the settings table
$table = TABLE_PREFIX .'mod_droplets_settings';
$database->simple_query("CREATE TABLE IF NOT EXISTS `".$table."` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`attribute` VARCHAR(50) NOT NULL DEFAULT '0',
	`value` VARCHAR(50) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	)"
);

// check for errors
if ($database->is_error())
{
	$droplets_install_errors[] = $database->get_error();
}

//	insert settings
//	Update for LEPTON-CMS 2.4: we're using prepare and execute for the "block" of "jobs"
$database->simple_query(
	"INSERT INTO `".TABLE_PREFIX ."mod_droplets_settings` (`id`, `attribute`, `value`) VALUES( ?, ?, ?)",
	array(
		array(1, 'Manage_backups', '1'),
		array(2, 'Import_droplets', '1'),
		array(3, 'Delete_droplets', '1'),
		array(4, 'Add_droplets', '1'),
		array(5, 'Export_droplets', '1'),
		array(6, 'Modify_droplets', '1'),
		array(7, 'Manage_perms', '1')
	)
);

// check for errors
if ($database->is_error())
{
	$droplets_install_errors[] = $database->get_error();
}

// create table droplets_load
$table = TABLE_PREFIX .'mod_droplets_load';
$database->simple_query("CREATE TABLE IF NOT EXISTS `".$table."` (
    `id` SERIAL,
    `register_name` VARCHAR(255) NOT NULL DEFAULT '' ,
    `register_type` VARCHAR(64) NOT NULL DEFAULT 'droplet' ,
    `page_id` INT(11) NOT NULL DEFAULT '0' ,
    `module_directory` VARCHAR(255) NOT NULL DEFAULT '',
    `file_type` VARCHAR(128) NOT NULL DEFAULT '',
    `file_name` VARCHAR(255) NOT NULL DEFAULT '',
    `file_path` TEXT NULL,
    `options` TEXT NULL,
    `timestamp` TIMESTAMP
    )");

// check for errors
if ($database->is_error())
{
	$droplets_install_errors[] = $database->get_error();
}

// import default droplets
if (!function_exists('droplet_install'))
{
	include_once LEPTON_PATH.'/modules/droplets/functions.php';
}
if (file_exists(dirname(__FILE__) . '/install/droplet_year.zip'))
{
	droplet_install(dirname(__FILE__) . '/install/droplet_check-css.zip', LEPTON_PATH . '/temp/unzip/');
	droplet_install(dirname(__FILE__) . '/install/droplet_EditThisPage.zip', LEPTON_PATH . '/temp/unzip/');
	droplet_install(dirname(__FILE__) . '/install/droplet_EmailFilter.zip', LEPTON_PATH . '/temp/unzip/');
	droplet_install(dirname(__FILE__) . '/install/droplet_LoginBox.zip', LEPTON_PATH . '/temp/unzip/');
	droplet_install(dirname(__FILE__) . '/install/droplet_Lorem.zip', LEPTON_PATH . '/temp/unzip/');
	droplet_install(dirname(__FILE__) . '/install/droplet_year.zip', LEPTON_PATH . '/temp/unzip/');
}

// Any errors to display?
if( count($droplets_install_errors) > 0 )
{
	//	If so, we force the admin to display them and die!
	$admin->print_error( implode("<br />\n", $droplets_install_errors) );

} else {

	// delete default droplets only if there are no errors during the installation!  
	if (!function_exists('rm_full_dir'))
	{
		include_once LEPTON_PATH.'/framework/functions/function.rm_full_dir.php';
	}
	rm_full_dir( LEPTON_PATH.'/modules/droplets/install' );
}

?>