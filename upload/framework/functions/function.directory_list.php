<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		directory_list
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
	 *	This function returns a recursive list of all subdirectories from a given directory
	 *
	 *	@access	public
	 *	@param	string	$directory: from this dir the recursion will start.
	 *	@param	bool	$show_hidden (optional): if set to TRUE also hidden dirs (.dir) will be shown.
	 *	@param	int		$recursion_deep (optional): An optional integer to test the recursions-deep at all.
	 *	@param	array	$aList (optional): A simple storage list for the recursion.
	 *	@param	string	$ignore (optional): This is the part of the "path" to be "ignored"
	 *
	 *	@return  array
	 *
	 *	@example:
	 *		/srv/www/httpdocs/wb/media/a/b/c/
	 *		/srv/www/httpdocs/wb/media/a/b/d/
	 *
	 *		if $ignore is set - directory_list('/srv/www/httpdocs/wb/media/') will return:
	 *		/a
	 *		/a/b
	 *		/a/b/c
	 *		/a/b/d
	 *
	 */
	function directory_list( $directory, $show_hidden = false, $recursion_deep = 0, &$aList = null, &$ignore = "" )
	{
		if ( $aList == null ) $aList = array();

		if ( is_dir( $directory ) ) {
			// Open the directory
			$dir = dir( $directory );
			if ( $dir != NULL ) {
				while ( false !== ( $entry = $dir->read() ) ) {
					// Skip hidden files
					if ( $entry[ 0 ] == '.' && $show_hidden == false ) continue;

					$temp_dir = $directory . "/" . $entry;
					if ( is_dir( $temp_dir ) ) {
						// Add dir and contents to list
						$aList[] = str_replace( $ignore, "", $temp_dir );
						$temp_result = directory_list( $temp_dir, $show_hidden, $recursion_deep + 1, $aList, $ignore );
					}
				}
				$dir->close();
			}
		}
		if ( $recursion_deep == 0 ) {
			natcasesort( $aList );
			return $aList;
		}
	}

?>