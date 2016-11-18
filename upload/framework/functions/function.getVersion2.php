<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		getVersion2
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
 *	As "version_compare" it self seems only got trouble 
 *	within words like "Alpha", "Beta" a.s.o. this function
 *	only modify the version-string in the way that these words are replaced by values/numbers.
 *
 *	E.g:	"1.2.3 Beta2" => "1.2.3.22"
 *			"0.1.1 ALPHA" => "0.1.1.1"
 *			
 *	@since	2.8.0	RC2
 *	@notice	2.8.2	Keys in $states have change within a leading dot to get correct results
 *					within a compare with problematic versions like e.g. "1.1.10 > 1.1.8 rc".
 *
 *	@param	string	A versionstring
 *	@return	string	The modificated versionstring
 *
 */
function getVersion2($version = "")
{
    
    $states = array(
        '.0' => "pre alpha",
        '.1' => "alpha",
        '.2' => "beta",
        '.4' => "rc",
        '.8' => "final",
        '.999' => "stable" // stable is a big number, because to avoid problems within e.g. RC12 or beta14
    );
    
    $version = strtolower($version);
    $version = str_replace(" ", "", $version);
    
    /**
     *	Transform e.g. "1.23" to "1.2.3.0.0". We need this for older modules pre WB 2.8.1 to get
     *	them run under lepton-cms.
     */
    $a = explode(".", $version);
    if (count($a) == 2)
    {
        $a[1] = ((int) $a[1] > 10) ? floor($a[1] / 10) . "." . ($a[1] % 10) : $a[1] . ".0.0";
        
        $version = $a[0] . "." . $a[1];
        
    }
    
    /**
     *	Short test if there are any chars. If not, we're handling the version as "stable".
     *	E.g. "1.0.1" will become "1.0.1.0stable".
     */
    $c = preg_match_all("/([a-z])/", $version, $matches);
    if (0 == $c)
    {
        // four digits?
        $temp = explode(".", $version);
        $n    = count($temp);
        if ($n < 4)
        {
            for ($i = 0; $i < (4 - $n); $i++)
                $version = $version . ".0";
        }
        $version .= "stable";
    }
    
    foreach ($states as $value => $keys)
        $version = str_replace($keys, $value, $version);
    
    /**
     *	Force the version-string to get at least 4 terms.
     *	E.g. 2.7 will become 2.7.0.0
     *
     */
    $temp_array = explode(".", $version);
    $n          = count($temp_array);
    if ($n < 4)
    {
        for ($i = 0; $i < (4 - $n); $i++)
            $version = $version . ".0";
    }
    
    return $version;
}

?>