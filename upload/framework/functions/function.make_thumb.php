<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		make_thumb
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
 *	Generate a thumbnail from an image
 *
 */
function make_thumb( $source, $destination, $size )
{
	// Check if GD is installed
	if(extension_loaded('gd') && function_exists('imageCreateFromJpeg'))
	{
		// First figure out the size of the thumbnail
		list($original_x, $original_y) = getimagesize($source);
		if ($original_x > $original_y)
		{
			$thumb_w = $size;
			$thumb_h = $original_y*($size/$original_x);
		}
		if ($original_x < $original_y)
		{
			$thumb_w = $original_x*($size/$original_y);
			$thumb_h = $size;
		}
		if ($original_x == $original_y)
		{
			$thumb_w = $size;
			$thumb_h = $size;	
		}
		// Now make the thumbnail
		$source = imageCreateFromJpeg($source);
		$dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
		imagecopyresampled($dst_img,$source,0,0,0,0,$thumb_w,$thumb_h,$original_x,$original_y);
		imagejpeg($dst_img, $destination);
		// Clear memory
		imagedestroy($dst_img);
		imagedestroy($source);
	   // Return true
		return true;
	} else {
		return false;
	}
} // end make_thumb()

?>