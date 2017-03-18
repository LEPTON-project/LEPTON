<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		  Website Baker Project, LEPTON Project
 * @copyright	   2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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

	// check if current user is admin
	$curr_user_is_admin = ( in_array( 1, $admin->get_groups_id() ) );

	// Include the functions file
	require_once( LEPTON_PATH . '/framework/summary.functions.php' );
	require_once( LEPTON_PATH . '/framework/summary.utf8.php' );

	// check if theme language file exists for the language set by the user (e.g. DE, EN)
	$lang = ( file_exists( THEME_PATH . '/languages/' . LANGUAGE . '.php' ) ) ? LANGUAGE : 'EN';

	// only a theme language file exists for the language, load it
	if ( file_exists( THEME_PATH . '/languages/' . $lang . '.php' ) )
	{
		include_once( THEME_PATH . '/languages/' . $lang . '.php' );
	}
	
	// *************************************************************************
	// Create new template object
	$tpl		= new Template( THEME_PATH . '/templates' );
	$tpl->debug = false;

	$tpl->set_file( 'page', 'settings.htt' );
	$tpl->set_block( 'page', 'main_block', 'main' );

	// fill in static text (language strings)
	admins_settings_fill_static_text($tpl);

	// *************************************************************************
	// extract sub blocks from main_block; it is VERY important to be careful
	// about the order! (innermost block first!)
	// *************************************************************************
	
	// first table (common settings)
	$tpl->set_block( 'main_block', 'page_level_limit_list_block', 'page_level_limit_list' );
	$tpl->set_block( 'main_block', 'show_page_level_limit_block', 'show_page_level_limit' );
	// checkbox blocks
	$tpl->set_block( 'main_block', 'show_checkbox_1_block', 'show_checkbox_1' );
	$tpl->set_block( 'main_block', 'show_checkbox_2_block', 'show_checkbox_2' );
	$tpl->set_block( 'main_block', 'show_checkbox_3_block', 'show_checkbox_3' );
	$tpl->set_block( 'main_block', 'show_redirect_timer_block', 'show_redirect_timer' );
	$tpl->set_block( 'main_block', 'group_list_block', 'group_list' );
	$tpl->set_block( 'main_block', 'error_reporting_list_block', 'error_reporting_list' );
	$tpl->set_block( 'main_block', 'show_php_error_level_block', 'show_php_error_level' );
	$tpl->set_block( 'main_block', 'editor_list_block', 'editor_list' );
	$tpl->set_block( 'main_block', 'show_wysiwyg_block', 'show_wysiwyg' );

	// second table (default settings)
	$tpl->set_block( 'main_block', 'language_list_block', 'language_list' );
	$tpl->set_block( 'main_block', 'charset_list_block', 'charset_list' );
	$tpl->set_block( 'main_block', 'show_charset_block', 'show_charset' );
	$tpl->set_block( 'main_block', 'timezone_list_block', 'timezone_list' );
	$tpl->set_block( 'main_block', 'date_format_list_block', 'date_format_list' );
	$tpl->set_block( 'main_block', 'time_format_list_block', 'time_format_list' );
	$tpl->set_block( 'main_block', 'template_list_block', 'template_list' );
	$tpl->set_block( 'main_block', 'theme_list_block', 'theme_list' );
	
	$tpl->set_block( 'main_block', 'search_template_list_block', 'search_template_list' );
	$tpl->set_block( 'main_block', 'show_search_block', 'show_search' );
	$tpl->set_block( 'main_block', 'search_footer_block', 'search_footer' );
	
	$tpl->set_block( 'main_block', 'show_admin_block', 'show_admin' );
	$tpl->set_block( 'main_block', 'show_access_block', 'show_access' );
	$tpl->set_block( 'main_block', 'access_footer_block', 'access_footer' );
	
	$tpl->set_block( 'main_block', 'mailer_settings_block', 'mailer_settings' );
	$tpl->set_block( 'mailer_settings_block', 'smtp_mailer_settings_block', 'smtp_mailer_settings' );
	$tpl->set_block( 'main_block', 'send_testmail_block', 'send_testmail' );

	// *************************************************************************
	// Work out if we have to show advanced options
	$is_advanced = ( isset( $_GET[ 'advanced' ] ) && ( $_GET[ 'advanced' ] == 'yes' ) );

	// Insert permissions values
	if ( $admin->get_permission( 'settings_advanced' ) != true )
	{
		$tpl->set_var( 'DISPLAY_ADVANCED_BUTTON', 'hide' );
	}
	else
	{
		$tpl->set_var( 'DISPLAY_ADVANCED_BUTTON', 'tabs-hide' );
	}

	if ( $is_advanced )
	{
		$tpl->set_var( 'DISPLAY_ADVANCED', '' );
		$tpl->set_var( 'ADVANCED', 'yes' );
		$tpl->set_var( 'JS_ADVANCED', 'no' );
		$tpl->set_var( 'ADVANCED_BUTTON', '&lt;&lt; ' . $TEXT[ 'HIDE_ADVANCED' ] );
		$tpl->set_var( 'ADVANCED_LINK', 'index.php?advanced=no' );
	}
	else
	{
		$tpl->set_var( 'DISPLAY_ADVANCED', ' style="display: none;"' );
		$tpl->set_var( 'ADVANCED', 'no' );
		$tpl->set_var( 'JS_ADVANCED', 'yes' );
		$tpl->set_var( 'ADVANCED_BUTTON', $TEXT[ 'SHOW_ADVANCED' ] . ' &gt;&gt;' );
		$tpl->set_var( 'ADVANCED_LINK', 'index.php?advanced=yes' );
	}

	// *************************************************************************
	// read the settings from the DB
	// we set a template var for each value, so we only have to handle the
	// special cases later
	$storrage = array();
	$sql	  = 'SELECT `name`, `value` FROM `' . TABLE_PREFIX . 'settings`';
	$database->execute_query( $sql, true, $storrage );
	foreach($storrage as &$row) {
		$tpl->set_var( strtoupper( $row[ 'name' ] ), $row[ 'value' ] );
	}
	// *************************************************************************
	// read the search settings from the DB
	$search_settings = array();
	$sql  = 'SELECT `name`,`value` FROM `' . TABLE_PREFIX . 'search` WHERE `extra` = \'\' ';
	$database->execute_query( $sql, true, $search_settings );
	foreach($search_settings as &$row) {
		$tpl->set_var( 'SEARCH_'.strtoupper( $row[ 'name' ] ), $row[ 'value' ] );
	}
	
	// *************************************************************************
	// these are the constants set in config.php and some specials (note the
	// trim(), for example)
	$tpl->set_var( array(
		'PAGES_DIRECTORY'   => trim( PAGES_DIRECTORY ),
		'LEPTON_PATH' 			=> LEPTON_PATH,
		'LEPTON_URL' 			=> LEPTON_URL,
		'THEME_URL' 		=> THEME_URL,
		'ADMIN_PATH' 		=> ADMIN_PATH,
		'ADMIN_URL' 		=> ADMIN_URL,
		'DATABASE_TYPE' 	=> '',
		'DATABASE_HOST' 	=> '',
		'DATABASE_USERNAME' => '',
		'DATABASE_NAME' 	=> '',
		'TABLE_PREFIX' 		=> TABLE_PREFIX,
		'FORM_NAME' 		=> 'settings',
		'ACTION_URL' 		=> 'save.php',
		'SERVER_EMAIL' 		=> SERVER_EMAIL ,
	) );

	// *************************************************************************
	// handle the special cases
	// *************************************************************************

	// ----- page level limit select box -----
	for ( $i = 1; $i <= 10; $i++ )
	{
		$tpl->set_var( 'NUMBER', $i );
		$tpl->set_var( 'SELECTED', (
			( PAGE_LEVEL_LIMIT == $i )
			? ' selected="selected"'
			: ''
			)
		);
		$tpl->parse( 'page_level_limit_list', 'page_level_limit_list_block', true );
	}

	// ----- trash -----
	if ( PAGE_TRASH == 'disabled' )
	{
		$tpl->set_var( 'PAGE_TRASH_DISABLED', ' checked="checked"' );
	}
	elseif ( PAGE_TRASH == 'inline' )
	{
		$tpl->set_var( 'PAGE_TRASH_INLINE', ' checked="checked"' );
	}

	// ----- page languages -----
	if ( defined( 'PAGE_LANGUAGES' ) && PAGE_LANGUAGES == true )
	{
		$tpl->set_var( 'PAGE_LANGUAGES_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'PAGE_LANGUAGES_DISABLED', ' checked="checked"' );
	}
	
	// ----- multiple menus -----
	if ( defined( 'MULTIPLE_MENUS' ) && MULTIPLE_MENUS == true )
	{
		$tpl->set_var( 'MULTIPLE_MENUS_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'MULTIPLE_MENUS_DISABLED', ' checked="checked"' );
	}

	// ----- home folders in media folder -----
	if ( HOME_FOLDERS )
	{
		$tpl->set_var( 'HOME_FOLDERS_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'HOME_FOLDERS_DISABLED', ' checked="checked"' );
	}

	// ----- manage sections -----
	if ( MANAGE_SECTIONS )
	{
		$tpl->set_var( 'MANAGE_SECTIONS_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'MANAGE_SECTIONS_DISABLED', ' checked="checked"' );
	}
	
	// ----- section blocks -----
	if ( defined( 'SECTION_BLOCKS' ) && SECTION_BLOCKS == true )
	{
		$tpl->set_var( 'SECTION_BLOCKS_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'SECTION_BLOCKS_DISABLED', ' checked="checked"' );
	}

	// ----- homepage redirection -----
	if ( defined( 'HOMEPAGE_REDIRECTION' ) && HOMEPAGE_REDIRECTION == true )
	{
		$tpl->set_var( 'HOMEPAGE_REDIRECTION_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'HOMEPAGE_REDIRECTION_DISABLED', ' checked="checked"' );
	}

	// ----- frontend login -----
	if ( FRONTEND_LOGIN )
	{
		$tpl->set_var( 'PRIVATE_ENABLED', ' checked="checked"' );
	}
	else
	{
		$tpl->set_var( 'PRIVATE_DISABLED', ' checked="checked"' );
	}

	// ----- frontend signup -----
	$sql = 'SELECT `group_id`, `name` FROM `' . TABLE_PREFIX . 'groups` WHERE `group_id` != 1';
	if ( ( $result = $database->query( $sql ) ) && ( $result->numRows() > 0 ) )
	{
		while ( $groups = $result->fetchRow() )
		{
			$tpl->set_var( 'ID', $groups[ 'group_id' ] );
			$tpl->set_var( 'NAME', $groups[ 'name' ] );
			$tpl->set_var( 'SELECTED', (
				( FRONTEND_SIGNUP == $groups[ 'group_id' ] )
				? ' selected="selected"' 
				: ""
				)
			);
			$tpl->parse( 'group_list', 'group_list_block', true );
		}
	}
	else
	{
		$tpl->set_var( 'ID', 'disabled' );
		$tpl->set_var( 'NAME', $MESSAGE[ 'GROUPS_NO_GROUPS_FOUND' ] );
		$tpl->parse( 'group_list', 'group_list_block', true );
	}

	// ----- error reporting -----
	require( ADMIN_PATH . '/interface/er_levels.php' );
	foreach ( $ER_LEVELS AS $value => &$title )
	{
		$tpl->set_var( 'VALUE', $value );
		$tpl->set_var( 'NAME', $title );
		$selected = ( ER_LEVEL == $value ) ? ' selected="selected"' : '';
		$tpl->set_var( 'SELECTED', $selected );
		$tpl->parse( 'error_reporting_list', 'error_reporting_list_block', true );
	}
	
	// ----- WYSIWYG modules -----
	// first value is 'none'
	$file		= 'none';
	$module_name = $TEXT[ 'NONE' ];
	$tpl->set_var( 'FILE', $file );
	$tpl->set_var( 'NAME', $module_name );
	$selected = ( !defined( 'WYSIWYG_EDITOR' ) || $file == WYSIWYG_EDITOR ) ? ' selected="selected"' : '';
	$tpl->set_var( 'SELECTED', $selected );
	$tpl->parse( 'editor_list', 'editor_list_block', true );

	// installed editors
	$sql  = 'SELECT `name`,`directory` FROM `' . TABLE_PREFIX . 'addons` ';
	$sql .= 'WHERE `type` = \'module\' ';
	$sql .= 'AND `function` = \'wysiwyg\' ';
	$sql .= 'ORDER BY `name`';
	
	if ( ( $result = $database->query( $sql ) ) && ( $result->numRows() > 0 ) )
	{
		while ( $addon = $result->fetchRow() )
		{
			$tpl->set_var( 'FILE', $addon[ 'directory' ] );
			$tpl->set_var( 'NAME', $addon[ 'name' ] );
			$selected = ( !defined( 'WYSIWYG_EDITOR' ) || $addon[ 'directory' ] == WYSIWYG_EDITOR ) 
				? ' selected="selected"' 
				: ''
				;
			$tpl->set_var( 'SELECTED', $selected );
			$tpl->parse( 'editor_list', 'editor_list_block', true );
		}
	}
	
	// ----- END FIRST TABLE -----
	
	// ----- SECOND TABLE -----
	
	// ----- language list -----
	$sql = 'SELECT `name`,`directory` FROM `' . TABLE_PREFIX . 'addons` ';
	$sql .= 'WHERE `type` = \'language\' ';
	$sql .= 'ORDER BY `name`';
	if ( ( $result = $database->query( $sql ) ) && $result->numRows() > 0 )
	{
		while ( $addon = $result->fetchRow() )
		{
			$l_codes[ $addon[ 'name' ] ] = $addon[ 'directory' ];
			$l_names[ $addon[ 'name' ] ] = $addon[ 'name' ]; // was: sorting-problem workaround
		}
		asort( $l_names );
		foreach ( $l_names as $l_name => &$v )
		{
			// Insert code and name
			$tpl->set_var( array(
				'CODE' => $l_codes[ $l_name ],
				'NAME' => $l_name,
				'FLAG' => THEME_URL . '/images/flags/' . strtolower( $l_codes[ $l_name ] )
			) );
			// Check if it is selected
			$tpl->set_var( 'SELECTED', (
				( DEFAULT_LANGUAGE == strtoupper($l_codes[ $l_name ] ) )
				? ' selected="selected"'
				: ""
				)
			);
			$tpl->parse( 'language_list', 'language_list_block', true );
		}
	}
	
	// ----- charsets -----
	require( ADMIN_PATH . '/interface/charsets.php' );
	foreach ( $CHARSETS AS $code => $title )
	{
		$tpl->set_var( 'VALUE', $code );
		$tpl->set_var( 'NAME', $title );
		$tpl->set_var( 'SELECTED', (
			/* ( DEFAULT_CHARSET == $code ) */
			( LINK_CHARSET == $code )
			? ' selected="selected"' 
			: ""
			)
		);
		$tpl->parse( 'charset_list', 'charset_list_block', true );
	}
	
	// ----- timezones -----
	require( LEPTON_PATH.'/framework/var.timezones.php' );
	foreach ( $timezone_table as $title )
	{
		$tpl->set_var( 'NAME', $title );
		$tpl->set_var( 'SELECTED', ( DEFAULT_TIMEZONE_STRING == $title ) ? ' selected="selected"' : '' );
		$tpl->parse( 'timezone_list', 'timezone_list_block', true );
	}
	
	// ----- date format -----
	$old_tz = date_default_timezone_get();
	date_default_timezone_set( DEFAULT_TIMEZONE_STRING );
	// Insert date format list
	require_once(LEPTON_PATH.'/framework/var.date_formats.php' );
	foreach ( $DATE_FORMATS AS $format => &$title )
	{
		$format = str_replace( '|', ' ', $format ); // Add's white-spaces (not able to be stored in array key)
		$tpl->set_var( 'VALUE', 
			( $format != 'system_default' )
			? $format
			: ""
		);
		$tpl->set_var( 'NAME', $title );
		$tpl->set_var( 'SELECTED', (
			( DEFAULT_DATE_FORMAT == $format )
			? ' selected="selected"'
			: ""
			)
		);
		$tpl->parse( 'date_format_list', 'date_format_list_block', true );
	}
	// set TZ back to user default
	date_default_timezone_set( $old_tz );
	
	// ----- time format -----
	require( LEPTON_PATH.'/framework/var.time_formats.php' );
	foreach ( $TIME_FORMATS AS $format => $title )
	{
		$format = str_replace( '|', ' ', $format ); // Add's white-spaces (not able to be stored in array key)
		$tpl->set_var( 'VALUE', (
			( $format != 'system_default' )
			? $format
			: ""
			)
		);
		$tpl->set_var( 'NAME', $title );
		$tpl->set_var( 'SELECTED', (
			( DEFAULT_TIME_FORMAT == $format )
			? ' selected="selected"'
			: ""
			)
		);
		$tpl->parse( 'time_format_list', 'time_format_list_block', true );
	}
	
	// ----- template list -----
	$sql = 'SELECT `name`,`directory`,`function` FROM `' . TABLE_PREFIX . 'addons` ';
	$sql .= 'WHERE `type` = \'template\' ';
	$sql .= 'AND `function` != \'theme\' ';
	$sql .= 'ORDER BY `name`';
	if ( ( $result = $database->query( $sql ) ) && ( $result->numRows() > 0 ) )
	{
		while ( $addon = $result->fetchRow() )
		{
			$deprecated = ( $addon[ 'function' ] == "" ? " style='color:#FF0000;'" : "" );

			$tpl->set_var( 'FILE', $addon[ 'directory' ] );
			$tpl->set_var( 'NAME', $addon[ 'name' ] . ( $addon[ 'function' ] == "" ? " !" : "" ) );
			$selected = ( ( $addon[ 'directory' ] == DEFAULT_TEMPLATE ) ? ' selected="selected"' : '' );

			$tpl->set_var( 'SELECTED', $selected . $deprecated );
			$tpl->parse( 'template_list', 'template_list_block', true );
		}
	}
	
	// ----- backend theme list -----
	$sql = 'SELECT `name`,`directory` FROM `' . TABLE_PREFIX . 'addons` ';
	$sql .= 'WHERE `type` = \'template\' ';
	$sql .= 'AND `function` = \'theme\' ';
	$sql .= 'ORDER BY `name`';
	if ( ( $result = $database->query( $sql ) ) && ( $result->numRows() > 0 ) )
	{
		while ( $addon = $result->fetchRow() )
		{
			$tpl->set_var( 'FILE', $addon[ 'directory' ] );
			$tpl->set_var( 'NAME', $addon[ 'name' ] );
			if ( ( $addon[ 'directory' ] == DEFAULT_THEME ) ? $selected = ' selected="selected"' : $selected = '' );
			$tpl->set_var( 'SELECTED', $selected );
			$tpl->parse( 'theme_list', 'theme_list_block', true );
		}
	}

	// ----- search visibility -----
	switch( SEARCH )
	{
		case 'private': $tpl->set_var( 'PRIVATE_SEARCH', ' selected="selected"' );
			break;
			
		case 'registered': $tpl->set_var( 'REGISTERED_SEARCH', ' selected="selected"' );
			break;
			
		case 'none': $tpl->set_var( 'NONE_SEARCH', ' selected="selected"' );
			break;
	}

	// ----- search templates -----
	$search_array = array();
	$sql		  = 'SELECT `name`,`value` FROM `' . TABLE_PREFIX . 'search` WHERE `extra` = \'\' ';
	if ( ( $res_search = $database->query( $sql ) ) && ( $res_search->numRows() > 0 ) )
	{
		while ( $row = $res_search->fetchRow() )
		{
			$search_array[ $row[ 'name' ] ] = htmlspecialchars( ( $row[ 'value' ] ) );
		}
		$tpl->set_var( array( strtoupper( 'SEARCH_' . $row[ 'name' ] ) => $row[ 'value' ] ) );
		$search_template = $search_array[ 'template' ];
	}

	$search_template = ( ( $search_template == DEFAULT_TEMPLATE ) || ( $search_template == '' ) ) ? '' : $search_template;
	$selected		= ( ( $search_template == '' ) ) ? ' selected="selected"' : $selected = '';
	$tpl->set_var( array(
		'FILE' => '',
		'NAME' => $TEXT[ 'SYSTEM_DEFAULT' ],
		'SELECTED' => $selected
	) );
	$tpl->parse( 'search_template_list', 'search_template_list_block', true );
	
	$sql = 'SELECT `name`,`directory` FROM `' . TABLE_PREFIX . 'addons` ';
	$sql .= 'WHERE `type` = \'template\' ';
	$sql .= 'AND `function` = \'template\' ';
	$sql .= 'ORDER BY `name`';
	if ( ( $result = $database->query( $sql ) ) && ( $result->numRows() > 0 ) )
	{
		while ( $addon = $result->fetchRow() )
		{
			$tpl->set_var( 'FILE', $addon[ 'directory' ] );
			$tpl->set_var( 'NAME', $addon[ 'name' ] );
			$selected = ( $addon[ 'directory' ] == $search_template ) ? ' selected="selected"' : '';
			$tpl->set_var( 'SELECTED', $selected );
			$tpl->parse( 'search_template_list', 'search_template_list_block', true );
		}
	}
	
	// ----- operating system -----
	switch( OPERATING_SYSTEM ) 
	{
		case 'linux': 
			$tpl->set_var( 'LINUX_SELECTED', ' checked="checked"' );
			$tpl->set_var( 'show77', 'block' );
			break;
			
		case 'windows':
			$tpl->set_var( 'WINDOWS_SELECTED', ' checked="checked"' );
			$tpl->set_var( 'show77', 'none' );
			break;
	}
	$tpl->set_var( 'WORLD_WRITEABLE_SELECTED', 
		(STRING_FILE_MODE == '0777' && STRING_DIR_MODE == '0777' ) 
		? ' checked="checked"' 
		: ''
	);
	$checked = ' checked="checked"';

	
	// ----- mail settings -----
	switch( MAILER_ROUTINE )
	{
		case 'phpmail':
			$tpl->set_var( 'PHPMAIL_SELECTED', ' checked="checked"' );
			$tpl->set_var( 'SMTP_VISIBILITY', ' style="display: none;"' );
			$tpl->set_var( 'SMTP_VISIBILITY_AUTH', ' style="display: none;"' );
			break;
		
		case 'smtp':
			$tpl->set_var( 'SMTPMAIL_SELECTED', ' checked="checked"' );
			$tpl->set_var( 'SMTP_VISIBILITY', '' );
			break;
	}

	// Work-out if SMTP authentification should be checked
	if ( MAILER_SMTP_AUTH )
	{
		$tpl->set_var( 'SMTP_AUTH_SELECTED', ' checked="checked"' );
		$tpl->set_var( 'SMTP_VISIBILITY_AUTH', ( 
			( MAILER_ROUTINE == 'smtp' )
			? ''
			: ' style="display: none;"'
			)
		);
	}
	else
	{
		$tpl->set_var( 'SMTP_VISIBILITY_AUTH', ' style="display: none;"' );
	}
	
	// now we have filled all blocks, but we may not show them all, depending
	// on $is_advanced (see above)
	if ( $is_advanced )
	{
		$tpl->parse( 'show_page_level_limit', 'show_page_level_limit_block', true );
		$tpl->parse( 'show_checkbox_1', 'show_checkbox_1_block', true );
		$tpl->parse( 'show_checkbox_2', 'show_checkbox_2_block', true );
		$tpl->parse( 'show_checkbox_3', 'show_checkbox_3_block', true );
		$tpl->parse( 'show_php_error_level', 'show_php_error_level_block', true );
		$tpl->parse( 'show_charset', 'show_charset_block', true );
		$tpl->parse( 'show_wysiwyg', 'show_wysiwyg_block', true );
		$tpl->parse( 'show_redirect_timer', 'show_redirect_timer_block', true );
		$tpl->parse( 'show_search', 'show_search_block', false );
		$tpl->set_var( 'ADVANCED_FILE_PERMS_ID', 'file_perms_box' );
		$tpl->set_var( 'BASIC_FILE_PERMS_ID', 'show' );
	}
	else
	{
		// remove blocks
		$tpl->unset_var( 'show_page_level_limit' );
		$tpl->unset_var( 'show_checkbox_1' );
		$tpl->unset_var( 'show_checkbox_2' );
		$tpl->unset_var( 'show_checkbox_3' );
		$tpl->unset_var( 'show_php_error_level' );
		$tpl->unset_var( 'show_charset' );
		$tpl->unset_var( 'show_wysiwyg' );
		$tpl->unset_var( 'show_redirect_timer' );
		$tpl->unset_var( 'show_search' );
		$tpl->set_var( 'BASIC_FILE_PERMS_ID', 'file_perms_box' );
		$tpl->set_var( 'ADVANCED_FILE_PERMS_ID', 'show' );
	}

	// some options are for superuser only
	if ( $curr_user_is_admin )
	{
		$tpl->parse( 'show_admin', 'show_admin_block', true );
		$tpl->parse( 'show_access_menu', 'show_access_menu_block', true );
		$tpl->parse( 'mailer_menu', 'mailer_menu_block', true );
		$tpl->parse( 'mailer_settings', 'mailer_settings_block', true );
		if ( $is_advanced )
		{
			$tpl->parse( 'show_access', 'show_access_block', true );
			$tpl->parse( 'smtp_mailer_settings', 'smtp_mailer_settings_block', true );
		}
		else
		{
			$tpl->unset_var( 'show_access' );
			$tpl->unset_var( 'smtp_mailer_settings' );
		}
		$tpl->parse( 'search_footer', 'search_footer_block', true );
		$tpl->parse( 'access_footer', 'access_footer_block', true );
		$tpl->parse( 'send_testmail', 'send_testmail_block', true );

	}
	else
	{
		$tpl->unset_var( 'search_footer_block' );
		$tpl->unset_var( 'access_footer_block' );
		$tpl->unset_var( 'send_testmail_block' );
		$tpl->unset_var( 'show_admin' );
		$tpl->unset_var( 'show_access_menu' );
		$tpl->unset_var( 'mailer_menu' );
		$tpl->unset_var( 'mailer_settings' );
		$tpl->unset_var( 'show_access' );
		$tpl->unset_var( 'smtp_mailer_settings' );
	}

	// Parse template objects output
	$tpl->parse( 'main', 'main_block', false );
	$output = $tpl->finish( $tpl->pparse( 'output', 'page' ) );
}   // end build_settings

// *************************************************************************
// insert language strings into main block
function admins_settings_fill_static_text( &$tpl )
{
	global $HEADING, $TEXT, $MESSAGE;

	// headings
	foreach( $HEADING as $key => &$value )
	{
		if ( is_array( $value ) )
		{
			continue;
		}
		$tpl->set_var( array(
			'HEADING_'.$key => $value
		) );
	}

	// other text
	foreach ( $TEXT as $key => &$value )
	{
		if ( is_array( $value ) )
		{
			continue;
		}
		$tpl->set_var( array(
			'TEXT_'.$key => $value
		) );
	}

	// there are some that do not have the expected prefix or need some tweaking...
	$tpl->set_var( array(
		// ----- headings -----
		'HEADING_ADVANCE_SETTINGS'	  => $TEXT[ 'VISIBILITY' ],
		// ----- other text -----
		'HELP_LEPTOKEN_LIFETIME' 		=> $TEXT[ 'HELP_LEPTOKEN_LIFETIME' ],
		'HELP_MAX_ATTEMPTS' 			=> $TEXT[ 'HELP_MAX_ATTEMPTS' ],
		'TEXT_RENAME_FILES_ON_UPLOAD' 	=> $TEXT[ 'ALLOWED_FILETYPES_ON_UPLOAD' ],
		'TEXT_MANAGE_SECTIONS' 			=> $HEADING[ 'MANAGE_SECTIONS' ],
		'TEXT_FILES' 					=> strtoupper( substr( $TEXT[ 'FILES' ], 0, 1 ) ) . substr( $TEXT[ 'FILES' ], 1 ),
		'TEXT_MAILER_SENDTESTMAIL' 	=> $TEXT[ 'MAILER_SEND_TESTMAIL' ],
		'MODE_SWITCH_WARNING' 			=> $MESSAGE[ 'SETTINGS_MODE_SWITCH_WARNING' ],
		'WORLD_WRITEABLE_WARNING' 		=> $MESSAGE[ 'SETTINGS_WORLD_WRITEABLE_WARNING' ],
	) );

}   // end function admins_settings_fill_static_text()

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