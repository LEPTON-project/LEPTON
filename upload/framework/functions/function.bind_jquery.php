<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		bind_jquery
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

function bind_jquery( $file_id = 'jquery' )
{
	
	$jquery_links = '';
	/* include the Javascript jquery api  */
	if ( $file_id == 'jquery' AND file_exists( LEPTON_PATH . '/modules/lib_jquery/jquery-core/jquery-core.min.js' ) )
	{
		$jquery_links .= "<script type=\"text/javascript\">\n" . "var URL = '" . LEPTON_URL . "';\n" . "var LEPTON_URL = '" . LEPTON_URL . "';\n" . "var TEMPLATE_DIR = '" . TEMPLATE_DIR . "';\n" . "</script>\n";				
		$jquery_links .= '<script src="' . LEPTON_URL . '/modules/lib_jquery/jquery-core/jquery-core.min.js" type="text/javascript"></script>' . "\n";
		$jquery_frontend_file = TEMPLATE_DIR . '/jquery_frontend.js';
		$jquery_links .= file_exists( str_replace( LEPTON_URL, LEPTON_PATH, $jquery_frontend_file ) ) ? '<script src="' . $jquery_frontend_file . '" type="text/javascript"></script>' . "\n" : '';
	}
	return $jquery_links;
}
?>