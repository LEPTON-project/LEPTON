<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		media_dirs_rw
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
	 * @param object &$wb: $wb from frontend or $admin from backend
	 * @return array: list of rw-dirs
	 * @description: returns a list of directories beyound /wb/media which are ReadWrite for current user
	 *
	 *  M.f.i.!  Copy and paste crap!
	 *
	 *  2011-08-22 Bianka Martinovic
	 *      used only in admins/media/index.php, should be moved there
	 */
	function media_dirs_rw( &$wb )
	{
		global $database;
		// if user is admin or home-folders not activated then there are no restrictions
		// at first read any dir and subdir from /media
		$full_list  = directory_list( LEPTON_PATH . MEDIA_DIRECTORY );
		$allow_list = array();
		if ( ( $wb->get_user_id() == 1 ) || !HOME_FOLDERS )
		{
			return $full_list;
		} //( $wb->get_user_id() == 1 ) || !HOME_FOLDERS
		// add own home_folder to allow-list
		if ( $wb->get_home_folder() )
		{
			$allow_list[] = $wb->get_home_folder();
		} //$wb->get_home_folder()
		// get groups of current user
		$curr_groups = $wb->get_groups_id();
		// if current user is in admin-group
		if ( ( $admin_key = array_search( '1', $curr_groups ) ) !== false )
		{
			// remove admin-group from list
			unset( $curr_groups[ $admin_key ] );
			// search for all users where the current user is admin from
			foreach ( $curr_groups as $group )
			{
				$sql = 'SELECT `home_folder` FROM `' . TABLE_PREFIX . 'users` ';
				$sql .= 'WHERE (FIND_IN_SET(\'' . $group . '\', `groups_id`) > 0) AND `home_folder` <> \'\' AND `user_id` <> ' . $wb->get_user_id();
				if ( ( $res_hf = $database->query( $sql ) ) != null )
				{
					while ( false !== ( $rec_hf = $res_hf->fetchRow() ) )
					{
						$allow_list[] = $rec_hf[ 'home_folder' ];
					} //false !== ( $rec_hf = $res_hf->fetchRow() )
				} //( $res_hf = $database->query( $sql ) ) != null
			} //$curr_groups as $group
		} //( $admin_key = array_search( '1', $curr_groups ) ) !== false
		$tmp_array = $full_list;
		// create a list for readwrite dir
		$array     = array();
		while ( sizeof( $tmp_array ) > 0 )
		{
			$tmp = array_shift( $tmp_array );
			$x   = 0;
			while ( $x < sizeof( $allow_list ) )
			{
				if ( strpos( $tmp, $allow_list[ $x ] ) )
				{
					$array[] = $tmp;
				} //strpos( $tmp, $allow_list[ $x ] )
				$x++;
			} //$x < sizeof( $allow_list )
		} //sizeof( $tmp_array ) > 0
		$tmp       = array();
		$full_list = array_merge( $tmp, $array );
		return $full_list;
	} // end function media_dirs_rw()

?>