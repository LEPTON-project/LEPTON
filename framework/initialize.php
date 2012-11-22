<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		  Website Baker Project, LEPTON Project
 * @copyright	   2004-2010, Website Baker Project
 * @copyright	   2010-2011, LEPTON Project
 * @link			http://www.LEPTON-cms.org
 * @license		 http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version		 $Id: initialize.php 1383 2011-11-17 15:15:26Z aldus $
 *
 */


// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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

//set_include_path(get_include_path() . PATH_SEPARATOR . WB_PATH);
if (file_exists(dirname(__FILE__).'/class.database.php')) {

	require_once(dirname(__FILE__).'/sys.constants.php');
	require_once(dirname(__FILE__).'/class.database.php');

	// Create database class
	global $database;
	if (!is_object($database)) $database = new database();
	
	// Get website settings (title, keywords, description, header, and footer)

	$sql = 'SELECT `name`,`value` FROM `'.TABLE_PREFIX.'settings` ORDER BY `name`';
	$storrage = array();
	$database->get_all( $sql, $storrage );
	foreach($storrage as &$row) {
		if (preg_match( '/^[0-7]{1,4}$/', $row['value'] ) == true) {
			$value = $row['value'];
		}
		elseif (preg_match( '/^[0-9]+$/S', $row['value'] ) == true) {
			$value = intval( $row['value'] );
		}
		elseif ($row['value'] == 'false') {
			$value = false;
		}
		elseif ($row['value'] == 'true') {
			$value = true;
		}
		else {
			$value = $row['value'];
		}
		$temp_name = strtoupper( $row['name'] ); 
		if (!defined($temp_name)) define( $temp_name , $value );
	}
	unset( $row );
		
	// define WB_VERSION for backward compatibillity and for checks within addon.precheck.inc.php
	if (!defined('WB_VERSION')) define('WB_VERSION', '2.8.1');
	
	$string_file_mode = STRING_FILE_MODE;
	define( 'OCTAL_FILE_MODE', (int) octdec( $string_file_mode ));
	$string_dir_mode = STRING_DIR_MODE;
	define( 'OCTAL_DIR_MODE', (int) octdec( $string_dir_mode ));
	if (!defined( 'WB_INSTALL_PROCESS' )) {
		// get CAPTCHA and ASP settings

		$sql = 'SELECT * FROM `'.TABLE_PREFIX.'mod_captcha_control` LIMIT 1';
		if ($get_settings = $database->query( $sql )) {
			if ($get_settings->numRows( ) == 0) {
				die( "CAPTCHA-Settings not found" );
			}
			$setting = $get_settings->fetchRow( MYSQL_ASSOC );
			
			define( 'ENABLED_CAPTCHA', (($setting['enabled_captcha'] == '1') ? true : false) );
			
			define( 'ENABLED_ASP', (($setting['enabled_asp'] == '1') ? true : false) );
			
			define( 'CAPTCHA_TYPE', $setting['captcha_type'] );
			define( 'ASP_SESSION_MIN_AGE', (int) $setting['asp_session_min_age'] );
			define( 'ASP_VIEW_MIN_AGE', (int) $setting['asp_view_min_age'] );
			define( 'ASP_INPUT_MIN_AGE', (int) $setting['asp_input_min_age'] );
			
			unset( $setting );
		}
	}

	/** 
	 *	set error-reporting
	 */
	if (is_numeric( ER_LEVEL )) {
		error_reporting( ER_LEVEL );
		if( ER_LEVEL > 0 ) ini_set('display_errors', 1);
	}
	
	// Start a session
	if (!defined( 'SESSION_STARTED' )) {
		session_name( APP_NAME.'sessionid' );
		
		$cookie_settings = session_get_cookie_params();
		session_set_cookie_params(
			3*3600,		// three hours
			$cookie_settings["path"],
			$cookie_settings["domain"],
			(strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, 5)) === 'https'),	// secure-bool
			true	// http only
		);
		unset( $cookie_settings );

		session_start();
		define( 'SESSION_STARTED', true );
	}
	if (defined( 'ENABLED_ASP' ) && ENABLED_ASP && !isset ($_SESSION['session_started']))
		$_SESSION['session_started'] = time( );
	
	// Get users language
	if (isset ($_GET['lang']) && $_GET['lang'] != '' && !is_numeric( $_GET['lang'] ) && strlen( $_GET['lang'] ) == 2) {
		define( 'LANGUAGE', strtoupper( $_GET['lang'] ));
		$_SESSION['LANGUAGE'] = LANGUAGE;
	} else {
		if (isset ($_SESSION['LANGUAGE']) && $_SESSION['LANGUAGE'] != '') {
			define( 'LANGUAGE', $_SESSION['LANGUAGE'] );
		} else {
			define( 'LANGUAGE', DEFAULT_LANGUAGE );
		}
	}
	// Load Language file
	if (!defined( 'LANGUAGE_LOADED' )) {
		if (!file_exists( WB_PATH.'/languages/'.LANGUAGE.'.php' )) {
			exit ('Error loading language file '.LANGUAGE.', please check configuration');
		} else {
			require_once (WB_PATH.'/languages/'.LANGUAGE.'.php');
		}
	}
	
	require_once( ADMIN_PATH.'/interface/timezones.php' );
	if (version_compare( PHP_VERSION, '5.3.0', '<' )) {
		// Disable magic_quotes_runtime
		set_magic_quotes_runtime( 0 );
	}
	
	/**
	 *	Setting the correct default timezone
	 *	to avoid "date" conflicts and warnings
	 *
	 */
	$timezone_string = (isset ($_SESSION['TIMEZONE_STRING']) ? $_SESSION['TIMEZONE_STRING'] : DEFAULT_TIMEZONESTRING );
	date_default_timezone_set($timezone_string);
	   
	// Get users date format
	define( 'DATE_FORMAT', (isset ($_SESSION['DATE_FORMAT']) ? $_SESSION['DATE_FORMAT'] : DEFAULT_DATE_FORMAT ) );
	
	// Get users time format
	define( 'TIME_FORMAT', (isset ($_SESSION['TIME_FORMAT']) ? $_SESSION['TIME_FORMAT'] : DEFAULT_TIME_FORMAT ) );
	
	// Set Theme dir
	define( 'THEME_URL', WB_URL.'/templates/'.DEFAULT_THEME );
	define( 'THEME_PATH', WB_PATH.'/templates/'.DEFAULT_THEME );
	
	$database->prompt_on_error( PROMPT_MYSQL_ERRORS );
}
?>