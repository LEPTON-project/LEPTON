<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Twig Template Engine
 * @author          LEPTON Project
 * @copyright       2012-2018 LEPTON  
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

/**
 * Copyright (c) 2009-2018 by the Twig Team, see AUTHORS for more details.
 * Please see attached LICENSE FILE for Twig License
 * Documentation: https://twig.symfony.com/doc/2.x/
 * License: https://twig.symfony.com/license
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

$module_directory     = 'lib_twig';
$module_name          = 'Twig Library for LEPTON';
$module_function      = 'library';
$module_version       = '2.4.6.0';
$module_platform      = '4.x';
$module_delete 		  =  false;
$module_author        = 'sensiolabs.org, LEPTON Team';
$module_license       = 'GNU General Public License for LEPTON Addon, https://twig.symfony.com/license for Twig';
$module_description   = 'Twig PHP Template Engine';
$module_home          = 'http://lepton-cms.org/';
$module_guid          = '19fb9aba-7f31-4fee-81ea-1db03e83c6cc';

?>