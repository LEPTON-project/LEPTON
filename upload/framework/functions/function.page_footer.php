<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_header
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
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
 *	Function to get the page footer
 *
 *	@param	string	A date-format for the processtime.
 *	@param	bool	Return-mode: true returns the value, false will direct output the string.
 *
 */

	function page_footer( $date_format = 'Y', $mode = false )
	{
		global $starttime;
		$vars        = array(
			 '[YEAR]',
			'[PROCESS_TIME]' 
		);
		$processtime = array_sum( explode( " ", microtime() ) ) - $starttime;
		$values      = array(
			 date( $date_format ),
			$processtime 
		);
		$temp        = str_replace( $vars, $values, WEBSITE_FOOTER );
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