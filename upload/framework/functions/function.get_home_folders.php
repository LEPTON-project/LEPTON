<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_home_folders
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

	// Function to get a list of home folders not to show
	/**
	 *  M.f.i.!  Dietrich Roland Pehlke
	 *      I would like to keep the original comment unless i understand this one!
	 *      E.g. 'ami' is for me nothing more and nothing less than an 'admim'!
	 *
	 *      I'm also not acceppt the declaration of a function inside a function at all!
	 *      E.g. what happend if the function "get_home_folders" twice? Bang!
	 *
	 * 2011-08-22
	 *      Bianka Martinovic
	 *      The only file where this is used seems to be admins/media/index.php,
	 *      so in my opinion, it should be moved there
	 *
	 */
	function get_home_folders()
	{
		global $database, $admin;
		$home_folders = array();
		// Only return home folders is this feature is enabled
		// and user is not admin
		
		if ( HOME_FOLDERS && ( !$admin->ami_group_member( '1' ) ) )
		{
			$sql                = 'SELECT `home_folder` FROM `' . TABLE_PREFIX . 'users` WHERE `home_folder` != \'' . $admin->get_home_folder() . '\'';
			$query_home_folders = $database->query( $sql );
			if ( $query_home_folders->numRows() > 0 )
			{
				while ( false !== ( $folder = $query_home_folders->fetchRow() ) )
				{
					$home_folders[ $folder[ 'home_folder' ] ] = $folder[ 'home_folder' ];
				}
			}
			function remove_home_subs( $directory = '/', $home_folders = '' )
			{
				if ( false !== ( $handle = opendir( LEPTON_PATH . MEDIA_DIRECTORY . $directory ) ) )
				{
					// Loop through the dirs to check the home folders sub-dirs are not shown
					while ( false !== ( $file = readdir( $handle ) ) )
					{
						if ( $file[ 0 ] != '.' && $file != 'index.php' )
						{
							if ( is_dir( LEPTON_PATH . MEDIA_DIRECTORY . $directory . '/' . $file ) )
							{
								if ( $directory != '/' )
								{
									$file = $directory . '/' . $file;
								}
								else
								{
									$file = '/' . $file;
								}
								foreach ( $home_folders as $hf )
								{
									$hf_length = strlen( $hf );
									if ( $hf_length > 0 )
									{
										if ( substr( $file, 0, $hf_length + 1 ) == $hf )
										{
											$home_folders[ $file ] = $file;
										}
									}
								}
								$home_folders = remove_home_subs( $file, $home_folders );
							}
						}
					}
				}
				return $home_folders;
			}
			$home_folders = remove_home_subs( '/', $home_folders );
		}
		return $home_folders;
	}
?>