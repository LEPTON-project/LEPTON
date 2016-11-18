<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		getVersion
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

function getVersion($version, $strip_suffix = true)
{
    /**
     * This funtion creates a version string following the major.minor.revision convention
     * The minor and revision part of the version may not exceed 999 (three digits)
     * An optional suffix part can be added after revision (requires $strip_suffix = false)
     *
     * EXAMPLES: input --> output
     *	5 --> 5.000000; 5.0 --> 5.000000; 5.0.0 --> 5.000000
     * 	5.2 --> 5.002000; 5.20 --> 5.002000; 5.2.0 --> 5.002000
     * 	5.21 --> 5.002001; 5.2.1 --> 5.002001;
     * 	5.27.1 --> 5.027001; 5.2.71 --> 5.002071;
     * 	5.27.1 rc1 --> 5.027001_RC1 ($strip_suffix:= false)
     */
    // replace comma by decimal point
    $version = str_replace(',', '.', $version);
    
    // convert version into major.minor.revision numbering system
    list($major, $minor, $revision) = explode('.', $version, 3);
    
    // convert versioning style 5.21 into 5.2.1
    if ($revision == '' && strlen(intval($minor)) == 2)
    {
        $revision = substr($minor, -1);
        $minor    = substr($minor, 0, 1);
    }
    
    // extract possible non numerical suffix from revision part (e.g. Alpha, Beta, RC1)
    $suffix = strtoupper(trim(substr($revision, strlen(intval($revision)))));
    
    /*
    return (int)$major . '.' . sprintf('%03d', (int)$minor) . sprintf('%03d', (int)$revision) .
    (($strip_suffix == false && $suffix != '') ? '_' . $suffix : '');
    */
    // return standard version number (minor and revision numbers may not exceed 999)
    return sprintf('%d.%03d.%03d%s', (int) $major, (int) minor, (int) $revision, (($strip_suffix == false && $suffix != '') ? '_' . $suffix : ''));
}

?>