<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		mime_content_type
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
	 *	Get the mime-type of a given file.
	 *
	 *	@param	string	A filename within the complete local path.
	 *	@return	string	Returns the content type in MIME format, e.g. 'image/gif', 'text/plain', etc.
	 *	@notice			If nothing match, the function will return 'application/octet-stream'.
	 *
	 *	2011-10-04	Aldus:	The function has been marked as 'deprecated' by PHP/Zend.
	 *						For details please take a look at:
	 *						http://php.net/manual/de/function.mime-content-type.php
	 *
	 */
	if ( !function_exists( "mime_content_type" ) )
	{
		function mime_content_type( $filename )
		{
			$mime_types = array(
				 'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'text/html',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv', // images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml', // archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed', // audio/video
				'mp3' => 'audio/mpeg',
				'mp4' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime', // adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript', // ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint', // open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet' 
			);
			$temp       = explode( '.', $filename );
			$ext        = strtolower( array_pop( $temp ) );
			if ( array_key_exists( $ext, $mime_types ) )
			{
				return $mime_types[ $ext ];
			} //array_key_exists( $ext, $mime_types )
			elseif ( function_exists( 'finfo_open' ) )
			{
				$finfo    = finfo_open( FILEINFO_MIME );
				$mimetype = finfo_file( $finfo, $filename );
				finfo_close( $finfo );
				return $mimetype;
			} //function_exists( 'finfo_open' )
			else
			{
				return 'application/octet-stream';
			}
		} // end function mime_content_type()
	} //!function_exists( "mime_content_type" )

?>