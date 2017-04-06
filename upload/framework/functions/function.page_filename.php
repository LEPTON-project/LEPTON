<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_filename
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
 *	Function to convert a page title to a page filename.
 *	
 */
function page_filename( $string ) {
	
	//	Umlauts
	$myC = array(
		'ä'	=> "ae",
		'ü'	=> "ue",
		'ö'	=> "oe",
		'Ä'	=> "Ae",
		'Ü' => "Ue",
		"Ö" => "Oe",
		"ß" => "ss"
	);
	$string = str_replace( array_keys($myC), array_values($myC), $string);
	
	//	Brackets part 1
	$myBrackets = array(
		"("	=> "__28__",
		")"	=> "__29__",
		"[" => "__5b__",
		"]"	=> "__5d__"
	);
	$string = str_replace( array_keys($myBrackets), array_values($myBrackets), $string);
	
	// The rest to HTML-entities
	$string = htmlentities($string);
	
	// Now remove all bad characters
	$bad = array('\'','"','`','!','@','#','$','%','^','&','*','=','+','|','/','\\',';',':',',','?');
	$string = str_replace($bad, '', $string);
	
	// Replace multiple dots in filename to single dot and (multiple) dots at the end of the filename to nothing
	$string = preg_replace(array('/\.+/', '/\.+$/'), array('.', ''), $string);
	
	// Now replace spaces with page spcacer
	$string = trim($string);
	$string = preg_replace('/(\s)+/', PAGE_SPACER, $string);
	
	// Now convert to lower-case
	$string = strtolower($string);
	
	// If there are any weird language characters, this will protect us against possible problems they could cause
	$string = str_replace(array('%2F', '%'), array('/', ''), urlencode($string));
	
	//	Brackets part 2
	$string = str_replace( array_values($myBrackets), array_keys($myBrackets), $string);
	
	// Finally, return the cleaned string
	return $string;
}

?>