<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author		  LEPTON Project
 * @copyright	   2010-2016 LEPTON Project
 * @link			http://www.LEPTON-cms.org
 * @license		 http://www.gnu.org/licenses/gpl.html
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

function build_settings( &$admin, &$database )
{
	global $HEADING, $TEXT, $MESSAGE;

	global $parser;
	global $loader;
	
	// check if current user is admin
	$curr_user_is_admin = ( in_array( 1, $admin->get_groups_id() ) );

	// check if theme language file exists for the language set by the user (e.g. DE, EN)
	$lang = ( file_exists( THEME_PATH . '/languages/' . LANGUAGE . '.php' ) ) ? LANGUAGE : 'EN';

	// only a theme language file exists for the language, load it
	if ( file_exists( THEME_PATH . '/languages/' . $lang . '.php' ) )
	{
		include_once( THEME_PATH . '/languages/' . $lang . '.php' );
	}

	$all_settings = array();
	$database->execute_query(
		"SELECT `name`,`value` from `".TABLE_PREFIX."settings`",
		true,
		$all_settings,
		true
	);

	$settings = array();
	foreach($all_settings as &$ref) $settings[ $ref['name'] ] = $ref['value'];
	
	/**
	 *	Init template vars (-storage)
	 */

	//	get an instance from LEPTON_core as we are "call" this more than twice times next
	$oLEPTON = LEPTON_core::getInstance();

	$template_vars = array(
		'TEXT'		=> $TEXT,
		'MESSAGE'	=> $MESSAGE,
		'HEADING'	=> $HEADING,
		'FORM_NAME'	=> 'settings',
		'ADMIN_URL'	=> ADMIN_URL,		
		'ACTION_URL'	=> ADMIN_URL.'/settings/save.php',
		'leptoken'		=> LEPTON_tools::get_leptoken(),
		'error_levels'	=> $oLEPTON->get_errorlevels(),
		'timezones'		=> $oLEPTON->get_timezones(),
		'date_formats'	=> $oLEPTON->get_dateformats(),
		'time_formats'	=> $oLEPTON->get_timeformats()
	);

	//	[2.0] db fields of settings
	foreach($settings as $key => $value) $template_vars[ strtoupper( $key ) ] = $value;

	//	[2.1] Languages
	$database->execute_query(
		"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'language' ORDER BY `name`",
		true,
		$template_vars['languages'],
		true
	);
	
	//	[2.2] installed editors
	$database->execute_query(
		"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'module' AND `function`='wysiwyg' ORDER BY `name`",
		true,
		$template_vars['editors'],
		true
	);

	//	[2.3.1] template list
	$database->execute_query(
		"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'template' AND `function` != 'theme' ORDER BY `name`",
		true,
		$template_vars['templates'],
		true
	);

	//	[2.3.2] backend theme list
	$database->execute_query(
		"SELECT `name`,`directory` FROM `" . TABLE_PREFIX . "addons` WHERE `type` = 'template' AND `function` = 'theme' ORDER BY `name`",
		true,
		$template_vars['themes'],
		true
	);

	$return_str = ""; // LEPTON_tools::display( $template_vars['themes'], "pre", "ui message" );
	
	$return_str .= $parser->render(
		"@theme/settings.lte",
		$template_vars
	);
	
	return $return_str;
	
}

// test $_GET querystring can only be 1 or 2 (leptoken and may be advanced)
if ( isset( $_GET ) && sizeof( $_GET ) > 2 )
{
	die( 'Acess denied' );
}

// test if valid $admin-object already exists (bit complicated about PHP4 Compatibility)
if ( !( isset( $admin ) && is_object( $admin ) && ( get_class( $admin ) == 'admin' ) ) )
{
	require_once( LEPTON_PATH . '/framework/class.admin.php' );
}

//
if ( ( isset( $_GET[ 'advanced' ] ) && ( $_GET[ 'advanced' ] == 'no' ) ) || ( !isset( $_GET[ 'advanced' ] ) ) )
{
	$admin = new admin( 'Settings', 'settings_basic' );
}
elseif ( isset( $_GET[ 'advanced' ] ) && $_GET[ 'advanced' ] == 'yes' )
{
	$admin = new admin( 'Settings', 'settings_advanced' );
}

print build_settings( $admin, $database );

$admin->print_footer();

?>