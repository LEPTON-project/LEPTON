<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_link
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
  * Build a full link
  **/
 function page_link( $link )
 {
  // Check for :// in the link (used in URL's) as well as mailto:
  if ( strstr( $link, '://' ) == '' && substr( $link, 0, 7 ) != 'mailto:' )
  {
   return LEPTON_URL . PAGES_DIRECTORY . $link . PAGE_EXTENSION;
  } //strstr( $link, '://' ) == '' && substr( $link, 0, 7 ) != 'mailto:'
  else
  {
   return $link;
  }
 } // end function page_link()

?>