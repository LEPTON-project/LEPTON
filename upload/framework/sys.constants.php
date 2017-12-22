<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

/**
 *	definitions for LEPTON
 *
 */
if (!defined('CORE')) define('CORE', 'LEPTON');
if (!defined('VERSION')) define('VERSION', '4.0.0');
// for personal subversions only if needed
if (!defined('SUBVERSION')) define('SUBVERSION', '');

// define WB_VERSION for backward compatibillity and for checks within addon.precheck.inc.php
if (!defined('WB_VERSION')) define('WB_VERSION', '2.8.1');
if (!defined( "DEFAULT_TIMEZONESTRING" ) )	define( "DEFAULT_TIMEZONESTRING", "Europe/Berlin" );

/**
 * Constants used in field 'statusflags'of table 'users'      
 */
define( 'USERS_DELETED', 1 ); // user marked as deleted
define( 'USERS_ACTIVE', 2 ); // user is activated
define( 'USERS_CAN_SETTINGS', 4 ); // user can change own settings
define( 'USERS_CAN_SELFDELETE', 8 ); // user can delete himself
define( 'USERS_PROFILE_ALLOWED', 16 ); // user can create a profile page
define( 'USERS_PROFILE_AVAIL', 32 ); // user has fullfilled profile and can not be deleted via core
define( 'USERS_DEFAULT_SETTINGS', USERS_ACTIVE | USERS_CAN_SETTINGS );

/**
 * Constants used in Auth and Login module
 */
define( 'AUTH_MIN_PASS_LENGTH', 6 ); // minimum lenght a new password must have
define( 'AUTH_MAX_PASS_LENGTH', 128 ); // maximum lenght of a password.
define( 'AUTH_MIN_LOGIN_LENGTH', 3 ); // minimum lenght a login-name must have
define( 'AUTH_MAX_LOGIN_LENGTH', 128 ); // maximum lenght a login-name can have

?>