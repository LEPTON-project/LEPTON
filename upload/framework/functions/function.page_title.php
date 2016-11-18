<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_tiltle
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
 *	Function to get or display the current page title
 *
 *	@param	string	Spacer between the items; default is "-"
 *	@param	string	The template-string itself
 *	@param	boolean	The return-mode: 'true' will return the value, false will direct echo the string
 *	@example1	<title><?php page_title(' - ','[PAGE_TITLE][SPACER][MENU_TITLE][SPACER][WEBSITE_TITLE]'); ?></title>
 *	@example2	<title><?php page_title(' - ','[WEBSITE_TITLE][SPACER][PAGE_TITLE]'); ?></title> 
 * 
 */

	function page_title( $spacer = ' - ', $template = '[WEBSITE_TITLE][SPACER][PAGE_TITLE]', $mode = false )
	{
		$vars   = array(
			'[WEBSITE_TITLE]',
			'[PAGE_TITLE]',
			'[MENU_TITLE]',
			'[SPACER]' 
		);
		$values = array(
			WEBSITE_TITLE,
			PAGE_TITLE,
			MENU_TITLE,
			$spacer 
		);
		$temp   = str_replace( $vars, $values, $template );
		if ( true === $mode )
		{
			return $temp;
		}
		else
		{
			echo $temp;
			return true;
		}
	}


?>