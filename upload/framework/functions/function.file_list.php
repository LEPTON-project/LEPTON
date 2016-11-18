<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		file_list
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
	 *  Function to list all files in a given directory.
	 *
	 *  @param  string	Directory to list
	 *  @param  array	Optiona array with directories to skip, e.g. '.svn' or '.git'
	 *  @param  bool	Optional bool to list also hidden files, e.g. ".htaccess". Default is 'false'.
	 *	@param	string	Optional pattern for file types, e.g. 'png' or '(jpg|jpeg|gif)'.
	 *	@param	string	Optional string to strip from the full file path, e.g. LEPTON_PATH.
	 *
	 *  @return  array  Natsorted array within the files.
	 *
	 *	@example	file_list(LEPTON_PATH.'/modules/captcha_control/captcha/backgrounds', NULL, NULL, "png", LEPTON_PATH);
	 *				- Will return a list within all found .png files inside the folder captcha/backgrounds,
	 *				  without the LEPTON_PATH like "/modules/captcha_control/captcha/backgrounds/bg_10.png".
	 *
	 */
	function file_list( $directory, $skip = array(), $show_hidden = false, $file_type="", $strip="" )
	{
		$result_list = array();
		
		if ( is_dir( $directory ) )
		{
			$use_skip = ( count( $skip ) > 0 );

			$dir = dir( $directory );
			while ( false !== ( $entry = $dir->read() ) )
			{
				// Skip hidden files
				if ( ( $entry[ 0 ] == '.' ) && ( false == $show_hidden ) )
				{
					continue;
				}
				// Check if we to skip anything else
				if ( ( true === $use_skip ) && ( in_array( $entry, $skip ) ) )
				{
					continue;
				}
				
				if ( is_file( $directory . '/' . $entry ) )
				{
					// Add file to list
					$temp_file = $directory . '/' . $entry;
					if ($strip != "") $temp_file = str_replace($strip, "", $temp_file);
					
					if ($file_type === "") {
						$result_list[] = $temp_file;
					} else {
						if (preg_match('/\.'.$file_type.'$/i', $entry)) {
							$result_list[] = $temp_file;
						}
					}
				}
			}
			$dir->close();
		}
		natcasesort( $result_list );
		return $result_list;
	}

?>