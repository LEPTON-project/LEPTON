<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		scan_current_dir
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
	 *	Scan a given directory for dirs and files.
	 *
	 *	Used inside e.g. 'admins/addons/reload.php'
	 *
	 *	@param 	string	Optional path to be scanned; default is the current working directory (getcwd()).
	 *	@param	string	Optional pattern for file types, e.g. 'png' or '(jpg|jpeg|gif)'. Default is an empty string for all file(-types).
	 *
	 *	@return	array	Returns an assoc. array with the keys 'path' and 'filename'.
	 *	
	 */
	function scan_current_dir( $root = '', $file_type = '' )
	{
		$FILE = array(
			'path'	=> array(),
			'filename' => array()
		);
		
		if ( $root == '' ) $root = getcwd();
		
		if ( false !== ( $handle = dir( $root ) ) )
		{
			// Loop through the files and dirs an add to list
			while ( false !== ( $file = $handle->read() ) ) {
				if ( ($file[0] != '.') && ($file != 'index.php' ) )
				{
					if ( is_dir( $root . '/' . $file ) ) {
						$FILE[ 'path' ][] = $file;
					} else {
						if ($file_type === '') {
							$FILE[ 'filename' ][] = $file;
						} else {
							if (preg_match('/\.'.$file_type.'$/i', $file)) {
								$FILE[ 'filename' ][] = $file;
							}
						}
					}
				}
			}
			$handle->close();
		}
		
		natcasesort( $FILE[ 'path' ] );
		natcasesort( $FILE[ 'filename' ] );

		return $FILE;
	}

?>