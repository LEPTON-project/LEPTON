<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		sortPreCheckArray
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

function sortPreCheckArray($precheck_array)
{
    /**
     * This funtion sorts the precheck array to a common format
     */
    // define desired precheck order
    $key_order = array(
        'LEPTON_VERSION',
        'WB_VERSION',
        'WB_ADDONS',
        'ADDONS',		
        'PHP_VERSION',
        'PHP_EXTENSIONS',
        'PHP_SETTINGS',
        'CUSTOM_CHECKS'
    );
    
    $temp_array = array();
    foreach ($key_order as $key)
    {
        if (!isset($precheck_array[$key]))
            continue;
        $temp_array[$key] = $precheck_array[$key];
    }
    return $temp_array;
}

?>