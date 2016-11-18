<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		create_access_file
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

	/*
	 * create_access_file
	 * @param string $filename: full path and filename to the new access-file
	 * @param int $page_id: ID of the page for which the file should created
	 * @description: Create a new access file in the pages directory and subdirectory also if needed
	 */

	function create_access_file( $filename, $page_id )
	{
		global $admin, $MESSAGE;
		$pages_path    = LEPTON_PATH . PAGES_DIRECTORY;
		$rel_pages_dir = str_replace( $pages_path, '', dirname( $filename ) );
		$rel_filename  = str_replace( $pages_path, '', $filename );
		// root_check prevent system directories and important files from being overwritten if PAGES_DIR = '/'
		$denied        = false;
		if ( PAGES_DIRECTORY == '' )
		{
			$forbidden = array(
				'account',
				'admins',
				'framework',
				'include',
				'install',
				'languages',
				'media',
				'modules',
				'page',
				'search',
				'temp',
				'templates',
				'index.php',
				'config.php'
			);
			$search    = explode( '/', $rel_filename );
			// we need only the first level
			$denied    = in_array( $search[ 1 ], $forbidden );
		} //PAGES_DIRECTORY == ''
		if ( ( true === is_writable( $pages_path ) ) && ( false == $denied ) )
		{
			// First make sure parent folder exists
			$parent_folders = explode( '/', $rel_pages_dir );
			$parents        = '';
			foreach ( $parent_folders as $parent_folder )
			{
				if ( $parent_folder != '/' && $parent_folder != '' )
				{
					$parents .= '/' . $parent_folder;
					if ( !file_exists( $pages_path . $parents ) )
					{
						make_dir( $pages_path . $parents );
						change_mode( $pages_path . $parents );
					} //!file_exists( $pages_path . $parents )
				} //$parent_folder != '/' && $parent_folder != ''
			} //$parent_folders as $parent_folder
			$step_back = str_repeat( '../', substr_count( $rel_pages_dir, '/' ) + ( PAGES_DIRECTORY == "" ? 0 : 1 ) );
			$content   = '<?php' . "\n";
			$content .= "/**\n *\tThis file is autogenerated by LEPTON - Version: " . VERSION . "\n";
			$content .= " *\tDo not modify this file!\n */\n";
			$content .= "\t" . '$page_id = ' . $page_id . ';' . "\n";
			$content .= "\t" . 'require_once(\'' . $step_back . 'index.php\');' . "\n";
			$content .= '?>';
			/**
			 *  write the file
			 *
			 */
			$fp = fopen( $filename, 'w' );
			if ( $fp )
			{
				fwrite( $fp, $content, strlen( $content ) );
				fclose( $fp );
				/**
				 *  Chmod the file
				 *
				 */
				change_mode( $filename );
				/**
				 *	Looking for the index.php inside the current directory.
				 *	If not found - we just copy the master_index.php from the admin/pages
				 *
				 */
				$temp_index_path = dirname( $filename ) . "/index.php";
				if ( !file_exists( $temp_index_path ) )
				{
					$origin = ADMIN_PATH . "/pages/master_index.php";
					if ( file_exists( $origin ) )
						copy( $origin, $temp_index_path );
				} //!file_exists( $temp_index_path )
				
			} //$fp
			else
			{
				$admin->print_error( $MESSAGE[ 'PAGES_CANNOT_CREATE_ACCESS_FILE' ] . "<br />Problems while trying to open the file!" );
				return false;
			}
			return true;
		} //( true === is_writable( $pages_path ) ) && ( false == $denied )
		else
		{
			$admin->print_error( $MESSAGE[ 'PAGES_CANNOT_CREATE_ACCESS_FILE' ] );
			return false;
		}
	}

?>