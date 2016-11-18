<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          show_menu2
 * @author          Brofield, LEPTON Project
 * @copyright       2006-2010 Brofield
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org/sm2/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
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

$module_directory	= 'show_menu2';
$module_name		= 'show_menu2';
$module_function	= 'snippet';
$module_version		= '4.9.8';
$module_platform	= '1.x';
$module_delete		=  false;
$module_author		= 'Brodie Thiesfield, Aldus, erpe';
$module_license		= 'GNU General Public License';
$module_license_terms = '-';
$module_description	= 'A code snippet providing a complete replacement for the built-in menu functions. See <a href="http://www.lepton-cms.com/sm2/" target="_blank">documentation</a> for details or view the <a href="' .LEPTON_URL .'/modules/show_menu2/README.en.txt" target="_blank">readme</a> file.';
$module_guid		= 'b859d102-881d-4259-b91d-b5a1b57ab100';


?>