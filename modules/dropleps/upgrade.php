<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2012, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'WB_PATH' ) )
{
    include( WB_PATH . '/framework/class.secure.php' );
}
else
{
    $root  = "../";
    $level = 1;
    while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
    {
        $root .= "../";
        $level += 1;
    }
    if ( file_exists( $root . '/framework/class.secure.php' ) )
    {
        include( $root . '/framework/class.secure.php' );
    }
    else
    {
        trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
    }
}
// end include class.secure.php

$is_upgrade = false;
if ( file_exists( dirname(__FILE__).'/../droplets' ) ) {
    $is_upgrade = true;
}

// check if we already have a droplets table; leave it if yes
$result = $database->query("SHOW TABLES LIKE '".TABLE_PREFIX ."mod_dropleps';");
if ( $result->numRows() == 0 ) {
	$table = TABLE_PREFIX .'mod_dropleps';
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
	if( $database->is_error() ) {
	    $admin->print_error( $admin->lang->translate( 'Database Error: {{error}}', array( 'error' => $database->get_error() ) ) );
	}
}

// create the new permissions table
$result = $database->query("SHOW TABLES LIKE '".TABLE_PREFIX ."mod_dropleps_permissions';");
if ( $result->numRows() == 0 ) {
	$table = TABLE_PREFIX .'mod_dropleps_permissions';
	$database->query("CREATE TABLE `$table` (
		`id` INT(10) UNSIGNED NOT NULL,
		`edit_groups` VARCHAR(50) NOT NULL,
		`view_groups` VARCHAR(50) NOT NULL,
		PRIMARY KEY ( `id` )
		) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
	);
	// check for errors
	if( $database->is_error() ) {
	    $admin->print_error( $admin->lang->translate( 'Database Error: {{error}}', array( 'error' => $database->get_error() ) ) );
	}
}

// create the settings table
$result = $database->query("SHOW TABLES LIKE '".TABLE_PREFIX ."mod_dropleps_settings';");
if ( $result->numRows() == 0 ) {
	$table = TABLE_PREFIX .'mod_dropleps_settings';
	$database->query("CREATE TABLE `$table` (
		`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		`attribute` VARCHAR(50) NOT NULL DEFAULT '0',
		`value` VARCHAR(50) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
		) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
	);
	// check for errors
	if( $database->is_error() ) {
	    $admin->print_error( $admin->lang->translate( 'Database Error: {{error}}', array( 'error' => $database->get_error() ) ) );
	}
	// insert settings
	$database->query("INSERT INTO `".TABLE_PREFIX ."mod_dropleps_settings` (`id`, `attribute`, `value`) VALUES
	(1, 'manage_backups', '1'),
	(2, 'import_dropleps', '1'),
	(3, 'delete_dropleps', '1'),
	(4, 'add_dropleps', '1'),
	(5, 'export_dropleps', '1'),
	(6, 'modify_dropleps', '1'),
	(7, 'manage_perms', '1');
	");
}

// if it's an upgrade from the old droplets module...
if ( $is_upgrade ) {

	// create backup copy
	$temp_file = sanitize_path( WB_PATH . '/temp/droplets_module_backup.zip' );
	$temp_dir  = sanitize_path( WB_PATH . '/modules/droplets'                );

    $zip1      = $admin->get_helper( 'Zip', $temp_file )->config( 'removePath', $temp_dir );
    $file_list = $zip1->create( $temp_dir );
    if ( $file_list == 0 )
    {
        $admin->print_error( $admin->lang->translate( "Packaging error" ) . ' - ' . $zip1->errorInfo( true ) );
    }

	// remove the folder
	rm_full_dir( WB_PATH.'/modules/droplets' );

	// re-create the folder
	@mkdir( WB_PATH.'/modules/droplets', 0755 );

	// unpack the compatibility files
	$temp_file = sanitize_path( WB_PATH . '/modules/dropleps/install/droplets.zip' );
	$admin->get_helper( 'Zip', $temp_file )->config( 'Path', sanitize_path( WB_PATH.'/modules/droplets' ) )->extract( $temp_file );

}


?>