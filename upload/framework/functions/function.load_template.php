<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		load_template
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

	/**
	 *  Load template information from the info.php of a given template into the DB.
	 *
	 *  @param  string  Any valid directory
	 *
	 *  @notice  Keep in mind, that the variable-check is here the same as in
	 *      File: admins/templates/install.php.
	 *      The reason is, that it could be possible to call this function from
	 *      another script/codeblock direct. So in thees circumstances where would no
	 *      test at all, and the possibility to entry wrong data is given.
	 *
	 */
	function load_template( $directory )
	{
		global $database, $admin, $logger, $MESSAGE;
		
		if ( is_dir( $directory ) && file_exists( $directory . '/info.php' ) )
		{
			global $template_license, $template_directory, $template_author, $template_version, $template_function, $template_description, $template_platform, $template_name, $template_guid;
			
			require( $directory . "/info.php" );
			
			// Check that it doesn't already exist
			$sqlwhere = "`type` = 'template' AND `directory` = '" . $template_directory . "'";
			$sql      = "SELECT COUNT(*) FROM `" . TABLE_PREFIX . "addons` WHERE " . $sqlwhere;
			if ( $database->get_one( $sql ) )
			{
				$sql_job = "update";
			}
			else
			{
				$sql_job = "insert";
				$sqlwhere = "";
			}
			
			$fields = array(
				'directory'	=> $template_directory,
				'name'		=> $template_name,
				'description'	=> $template_description,
				'type' 		=> 'template',
				'function'	=> strtolower( $template_function ),
				'version'	=> $template_version,
				'platform'	=> $template_platform,
				'author' 	=> $template_author,
				'license'	=> $template_license
			);
			
			if ( isset( $template_guid ) ) $fields['guid'] = $template_guid;

			$database->build_and_execute(
				$sql_job,
				TABLE_PREFIX . "addons",
				$fields,
				$sqlwhere
			);
		
			if ( $database->is_error() )
				$admin->print_error( $database->get_error() );
		}
	}

?>