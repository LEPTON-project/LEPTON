<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		Website Baker Project, LEPTON Project
 * @copyright	2004-2010 Website Baker Project
 * @copyright	2010-2017 LEPTON Project
 * @link		https://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
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


	require_once( __DIR__."/functions/function.lepton_autoloader.php" );
	spl_autoload_register( "lepton_autoloader", true);
	
	LEPTON_tools::load(
		LEPTON_PATH . "/framework/summary.functions.php",
		LEPTON_PATH . "/framework/sys.constants.php",
		LEPTON_PATH . "/framework/var.timezones.php"
	);
	
	LEPTON_tools::register( "get_leptoken" );
	
	// Get an instance from class database
	$database = LEPTON_database::getInstance();
	
	// Get website settings (title, keywords, description, header, and footer)
	$sql = 'SELECT `name`,`value` FROM `' . TABLE_PREFIX . 'settings` ORDER BY `name`';
	$storage = array();
	$database->execute_query( $sql, true, $storage );
	foreach ( $storage as &$row )
	{
		if ( preg_match( '/^[0-7]{1,4}$/', $row[ 'value' ] ) == true )
		{
			$value = $row[ 'value' ];
		}
		elseif ( preg_match( '/^[0-9]+$/S', $row[ 'value' ] ) == true )
		{
			$value = intval( $row[ 'value' ] );
		}
		elseif ( $row[ 'value' ] == 'false' )
		{
			$value = false;
		}
		elseif ( $row[ 'value' ] == 'true' )
		{
			$value = true;
		}
		else
		{
			$value = $row[ 'value' ];
		}
		$temp_name = strtoupper( $row[ 'name' ] );
		if ( !defined( $temp_name ) )
			define( $temp_name, $value );
	}
	unset( $row );
	
	// define WB_VERSION for backward compatibillity and for checks within addon.precheck.inc.php
	if ( !defined( 'WB_VERSION' ) )
		define( 'WB_VERSION', '2.8.1' );
	
	$string_file_mode = STRING_FILE_MODE;
	define( 'OCTAL_FILE_MODE', (int) octdec( $string_file_mode ) );
	$string_dir_mode = STRING_DIR_MODE;
	define( 'OCTAL_DIR_MODE', (int) octdec( $string_dir_mode ) );
	if ( !defined( 'WB_INSTALL_PROCESS' ) )
	{
		// get CAPTCHA and ASP settings
		$setting = array();
		$sql = 'SELECT * FROM `' . TABLE_PREFIX . 'mod_captcha_control` LIMIT 1';
		$database->execute_query( $sql, true, $setting, false );
		
		if ( count($setting) == 0 )
		{
			die( "CAPTCHA-Settings not found" );
		}
		
		define( 'ENABLED_CAPTCHA', ( ( $setting[ 'enabled_captcha' ] == '1' ) ? true : false ) );		
		define( 'ENABLED_ASP', ( ( $setting[ 'enabled_asp' ] == '1' ) ? true : false ) );
		define( 'CAPTCHA_TYPE', $setting[ 'captcha_type' ] );
		define( 'ASP_SESSION_MIN_AGE', (int) $setting[ 'asp_session_min_age' ] );
		define( 'ASP_VIEW_MIN_AGE', (int) $setting[ 'asp_view_min_age' ] );
		define( 'ASP_INPUT_MIN_AGE', (int) $setting[ 'asp_input_min_age' ] );
		
		unset( $setting );	
	}
	
	/** 
	 *	set error-reporting
	 */
	if ( is_numeric( ER_LEVEL ) )
	{
		error_reporting( ER_LEVEL );
		if ( ER_LEVEL >= -1 )
			ini_set( 'display_errors', 1 );
	}
	
	// Start a session
	if ( !defined( 'SESSION_STARTED' ) )
	{
		session_name( APP_NAME . 'sessionid' );
		
		$cookie_settings = session_get_cookie_params();
		
		$server_is_https = (isset($_SERVER['HTTPS']))
			? ( strtolower( $_SERVER[ 'HTTPS' ] )  == 'on' )
			: false
			;
			
		session_set_cookie_params(
			3 * 3600, // three hours
			$cookie_settings[ "path" ],
			$cookie_settings[ "domain" ],
			$server_is_https, // secure-bool
			true // http only?
		);
		
		session_start();
		define( 'SESSION_STARTED', true );
		
		unset( $cookie_settings );
		unset( $server_is_https );
		
	}
	//	Try to set the session cookie to the current time + 3
	if( true === isset($_COOKIE[ APP_NAME . 'sessionid' ]))
	{
		setcookie(session_name(), $_COOKIE[ APP_NAME . 'sessionid' ], time() + (3*3600), '/');
	}
	
	if ( defined( 'ENABLED_ASP' ) && ENABLED_ASP && !isset( $_SESSION[ 'session_started' ] ) )
		$_SESSION[ 'session_started' ] = time();
	
	// Get users language
	if ( isset( $_GET[ 'lang' ] ) && $_GET[ 'lang' ] != '' && !is_numeric( $_GET[ 'lang' ] ) && strlen( $_GET[ 'lang' ] ) == 2 )
	{
		define( 'LANGUAGE', strtoupper( $_GET[ 'lang' ] ) );
		$_SESSION[ 'LANGUAGE' ] = LANGUAGE;
	}
	else
	{
		if ( isset( $_SESSION[ 'LANGUAGE' ] ) && $_SESSION[ 'LANGUAGE' ] != '' )
		{
			define( 'LANGUAGE', $_SESSION[ 'LANGUAGE' ] );
		}
		else
		{
			define( 'LANGUAGE', DEFAULT_LANGUAGE );
		}
	}
	
	// Load Language file
	if ( !defined( 'LANGUAGE_LOADED' ) )
	{
		if ( !file_exists( LEPTON_PATH . '/languages/' . LANGUAGE . '.php' ) )
		{
			exit( 'Error loading language file ' . LANGUAGE . ', please check configuration' );
		}
		else
		{
			require_once( LEPTON_PATH . '/languages/' . LANGUAGE . '.php' );
		}
	}
	
	/**
	 *	Setting the correct default timezone
	 *	to avoid "date" conflicts and warnings
	 *
	 */
	$timezone_string = ( isset( $_SESSION[ 'TIMEZONE_STRING' ] ) ? $_SESSION[ 'TIMEZONE_STRING' ] : DEFAULT_TIMEZONESTRING );
	date_default_timezone_set( $timezone_string );
	
	// Get users date format
	define( 'DATE_FORMAT', ( isset( $_SESSION[ 'DATE_FORMAT' ] ) ? $_SESSION[ 'DATE_FORMAT' ] : DEFAULT_DATE_FORMAT ) );
	
	// Get users time format
	define( 'TIME_FORMAT', ( isset( $_SESSION[ 'TIME_FORMAT' ] ) ? $_SESSION[ 'TIME_FORMAT' ] : DEFAULT_TIME_FORMAT ) );
	
	// Set Theme dir
	define( 'THEME_URL', LEPTON_URL . '/templates/' . DEFAULT_THEME );
	define( 'THEME_PATH', LEPTON_PATH . '/templates/' . DEFAULT_THEME );

	$database->prompt_on_error( PROMPT_MYSQL_ERRORS );

	LEPTON_tools::load( LEPTON_PATH . "/modules/lib_twig/library.php" );

?>