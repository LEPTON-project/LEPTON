<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
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

// see if there exists a frontend php file we can use or use the fallback
if (file_exists(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/preferences_form.php')) 
{
	require_once(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/preferences_form.php');
}
else
{	

/**	*********
 *	languages
 *
 */
$languages = array();
$database->execute_query( 
	"SELECT `directory`,`name` from `".TABLE_PREFIX."addons` where `type`='language'",
 	true,
 	$languages,
 	true
);

if ($database->is_error()) die ( LEPTON_tools::display( $database->get_error()) );

$language = array();
foreach($languages as $data)
{
	$language[] = array(
		'LANG_CODE' 	=>	$data['directory'],
		'LANG_NAME'		=>	$data['name'],
		'LANG_SELECTED'	=> (LANGUAGE == $data['directory']) ? " selected='selected'" : ""
	);
}


/**	****************
 *	default timezone
 *
 */
$timezone_table = LEPTON_core::get_timezones();
$timezone = array();
$currend_timezone_string = $wb->get_timezone_string();
foreach ($timezone_table as $title)
{
	$timezone[] = array(
		'TIMEZONE_NAME' => $title,
		'TIMEZONE_SELECTED' => ($currend_timezone_string == $title) ? ' selected="selected"' : ''
	);
}

/**	***********
 *	date format
 */
$date_format = array();
$user_time = true;

$DATE_FORMATS = LEPTON_core::get_dateformats();

foreach($DATE_FORMATS AS $format => $title) {
	
	if($format == "system_default") continue;
	
	if(DATE_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$sel = "selected='selected'";
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$sel = "selected='selected'";
	} else {
		$sel = '';	
	}			
	$date_format[] = array(
		'DATE_FORMAT_VALUE'	=>	$format,
		'DATE_FORMAT_TITLE'	=>	$title.( $format === DATE_FORMAT ? " (system default)" : "" ),
		'DATE_FORMAT_SELECTED' => $sel
	);

}

/**	***********
 *	time format
 */
$time_format = array();

$TIME_FORMATS = LEPTON_core::get_timeformats();
foreach($TIME_FORMATS AS $format => $title) {

	if($format == 'system_default') continue;

	if(TIME_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
		$sel = "selected='selected'";	
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
		$sel = "selected='selected'";
	} else {
		$sel = '';
	}			
	$time_format[] = array(
		'TIME_FORMAT_VALUE'	=>	$format,
		'TIME_FORMAT_TITLE'	=>	$title.( $format === TIME_FORMAT ? " (system default)" : "" ),
		'TIME_FORMAT_SELECTED' => $sel
	);
}

/**
 *	Build an access-prefernces-fom
 *	secure hash
 */
// if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
LEPTON_tools::register("random_string");
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'].random_string( 32 ) );
$_SESSION['wb_apf_hash'] = $hash;

/**
 *	Delete any "result_message" if there is one.
 */
if( true === isset($_SESSION['result_message']) ) unset($_SESSION['result_message']);

$data = array(
	'TEMPLATE_DIR' 				=>	TEMPLATE_DIR,
	'PREFERENCES_URL'			=>	PREFERENCES_URL,
	'LOGOUT_URL'				=>	LOGOUT_URL,
	'HEADING_MY_SETTINGS'		=>	$HEADING['MY_SETTINGS'],
	'HEADING_PREFERENCES'		=>	$MENU['PREFERENCES'],
	'TEXT_DISPLAY_NAME'			=>	$TEXT['DISPLAY_NAME'],
	'DISPLAY_NAME'				=>	$wb->get_display_name(),
	'TEXT_LANGUAGE'				=>	$TEXT['LANGUAGE'],
	'TEXT_TIMEZONE'				=>	$TEXT['TIMEZONE'],
	'TEXT_PLEASE_SELECT'		=>	$TEXT['PLEASE_SELECT'],
	'TEXT_DATE_FORMAT'			=>	$TEXT['DATE_FORMAT'],
	'TEXT_TIME_FORMAT'			=>	$TEXT['TIME_FORMAT'],
	'HEADING_MY_EMAIL'			=>	$HEADING['MY_EMAIL'],
	'TEXT_EMAIL'				=>	$TEXT['EMAIL'],
	'GET_EMAIL'					=>	$wb->get_email(),
	'HEADING_MY_PASSWORD'		=>	$HEADING['MY_PASSWORD'],
	'TEXT_CURRENT_PASSWORD'		=>	$TEXT['CURRENT_PASSWORD'],
	'TEXT_NEW_PASSWORD'			=>	$TEXT['NEW_PASSWORD'],
	'TEXT_RETYPE_NEW_PASSWORD'	=>	$TEXT['RETYPE_NEW_PASSWORD'],
	'TEXT_LOGOUT'				=>	$MENU['LOGOUT'],
	'TEXT_SAVE'					=>	$TEXT['SAVE'],
	'TEXT_RESET'				=>	$TEXT['RESET'],
	'USER_ID'					=>	(isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : '-1'),
	'r_time'					=>	TIME(),
	'HASH'						=>	$hash,
	'TEXT_NEED_CURRENT_PASSWORD' => $TEXT['NEED_CURRENT_PASSWORD'],
	'TEXT_ENABLE_JAVASCRIPT'	=> $TEXT['ENABLE_JAVASCRIPT'],
	'RESULT_MESSAGE'			=> (isset($_SESSION['result_message'])) ? $_SESSION['result_message'] : "",
	'AUTH_MIN_LOGIN_LENGTH'		=> AUTH_MIN_LOGIN_LENGTH,
	'language'	=> $language,
	'timezone'	=> $timezone,
	'date_format' => $date_format,
	'time_format' => $time_format
	
);

$oTWIG = lib_twig_box::getInstance();
$oTWIG->registerPath( dirname(__FILE__)."/templates/" );
// "login" got a slide another frontend path ... as THERE IS NO MODULE "login" we have to look ourself!
$oTWIG->registerPath( LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login/templates/" );
		
echo $oTWIG->render( 
	"preferences_form.lte",	//	template-filename
	$data			//	template-data
);

}
?>