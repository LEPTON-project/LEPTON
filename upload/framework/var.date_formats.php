<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @reformatted 2013-05-31
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

// Get the current time (in the users timezone if required)
$actual_time = time();

// Get "System Default"
$s = "";
if ( isset( $user_time ) && $user_time == true )
{
	$s = date( DEFAULT_DATE_FORMAT, $actual_time ) . ' (';
	$s .= ( isset( $TEXT[ 'SYSTEM_DEFAULT' ] ) ? $TEXT[ 'SYSTEM_DEFAULT' ] : 'System Default' ) . ')';
} //isset( $user_time ) && $user_time == true

// Add values to list
$DATE_FORMATS = array(
	 'system_default' => $s,
	'j.n.Y' => date( 'j.n.Y', $actual_time ) . ' (j.n.Y)',
	'm/d/Y' => date( 'm/d/Y', $actual_time ) . ' (M/D/Y)',
	'd/m/Y' => date( 'd/m/Y', $actual_time ) . ' (D/M/Y)',
	'm.d.Y' => date( 'm.d.Y', $actual_time ) . ' (M.D.Y)',
	'd.m.Y' => date( 'd.m.Y', $actual_time ) . ' (D.M.Y)',
	'm-d-Y' => date( 'm-d-Y', $actual_time ) . ' (M-D-Y)',
	'd-m-Y' => date( 'd-m-Y', $actual_time ) . ' (D-M-Y)',
	'D|M|d,|Y' => date( 'D M d, Y', $actual_time ),
	'M|d|Y' => date( 'M d Y', $actual_time ),
	'd|M|Y' => date( 'd M Y', $actual_time ),
	'jS|F,|Y' => date( 'jS F, Y', $actual_time ),
	'l,|jS|F,|Y' => date( 'l, jS F, Y', $actual_time ) 
);

?>