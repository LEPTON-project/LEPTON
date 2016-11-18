<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
 * 
 */


// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
}
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	}
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	}
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

echo ("<br /><p style='color:red;'>The file 'framework/functions-utf8.php' is marked deprecated in LEPTON 2.0.0 and will be deleted in next release!.</p> <br />");
echo ("<p style='color:green;'>Please use 'framework/summary.utf8.php' instead .</p> <br />");
echo ("<p>If you don't know what to do please <a href='http://forum.lepton-cms.org' target='_blank'>use the forum</a>.</p> <br />");
echo ("<p>If you want to learn more about deprecated function and files <a href='http://doc.lepton-cms.org' target='_blank'>please visit the documentation</a>.</p> <br />");
	require_once ( LEPTON_PATH . '/framework/summary.utf8.php' );
?>