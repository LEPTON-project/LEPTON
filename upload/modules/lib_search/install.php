<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          lib_search
 * @author          LEPTON Project
 * @copyright       2013-2017 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

global $database;
global $admin;

$table_fields="
    `search_id` INT NOT NULL auto_increment,
    `name` VARCHAR(255) NOT NULL DEFAULT '' ,
    `value` TEXT NOT NULL,
    `extra` TEXT NOT NULL,
    PRIMARY KEY (`search_id`)
";

LEPTON_handle::install_table('search', $table_fields);

// set default values for the LEPTON search
$field_values="
(1, 'module_order', 'wysiwyg', ''),
(2, 'max_excerpt', '15', ''),
(3, 'time_limit', '0', ''),
(4, 'cfg_search_keywords', 'true', ''),
(5, 'cfg_search_description', 'true', ''),
(6, 'cfg_search_non_public_content', 'false', ''),
(7, 'cfg_link_non_public_content', '', ''),
(8, 'cfg_show_description', 'true', ''),
(9, 'template', '', ''),
(10, 'cfg_search_images', 'true', ''),
(11, 'cfg_thumbs_width', '100', ''),
(12, 'cfg_content_image', 'first', ''),
(13, 'cfg_search_library', 'lib_search', ''),
(14, 'cfg_search_droplet', 'LEPTON_SearchResults', ''),
(15, 'cfg_search_use_page_id', '-1', '')
";
LEPTON_handle::insert_values('search', $field_values);


// import droplets
LEPTON_handle::install_droplets('lib_search', 'droplet_LEPTON_SearchBox.zip');
?>