<?php

/**
 *  @template       Lepton-Start
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2010-2013 CMS-LAB
 *  @license        http://creativecommons.org/licenses/by/3.0/
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
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

//initialize twig template engine
	global $parser;		// twig parser
	global $loader;		// twig file manager
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );

  
/**	*********
 *	languages
 *
 */

$query = "SELECT `directory`,`name` from `".TABLE_PREFIX."addons` where `type`='language'";
$result = $database->query( $query );
if (!$result) die ($database->get_error());

while( false != ($data = $result->fetchRow( MYSQL_ASSOC ) ) ) {

	$sel = (LANGUAGE == $data['directory']) ? " selected='selected'" : "";
  $langcode = $data['directory'];
  $langname = $data['name'];

 } 


/**	****************
 *	default timezone
 *
 */
global $timezone_table;
foreach ($timezone_table as $title)
{
	$t_name = $title;
	$t_selected = ($wb->get_timezone_string() == $title) ? ' selected="selected"' : '';
}

/**	***********
 *	date format
 *
 */

$user_time = true;
include (WB_PATH.'/framework/date_formats.php');
foreach($DATE_FORMATS AS $format => $title) {

	$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
	
	$value = ($format != 'system_default') ? $format : "";

	if(DATE_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$date_format = 'DATE_FORMAT_SELECTED'? 'selected="selected"':"";
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$date_format = 'DATE_FORMAT_SELECTED'? 'selected="selected"':"";
	} else {
		$date_format = 'DATE_FORMAT_SELECTED'? '':"";	
	}			
}

/**	***********
 *	time format
 *
 */

include(WB_PATH.'/framework/time_formats.php');
foreach($TIME_FORMATS AS $t_format => $t_title) {
	$t_format = str_replace('|', ' ', $t_format); // Add's white-spaces (not able to be stored in array key)

	$t_value = ($t_format != 'system_default') ? $t_format : "";

	if(TIME_FORMAT == $t_format AND !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
    $time_format = 'TIME_FORMAT_SELECTED'? 'selected="selected"':"";
	} elseif($t_format == 'system_default' AND isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
    $time_format = 'TIME_FORMAT_SELECTED'? 'selected="selected"':"";
	} else {
		$time_format = 'TIME_FORMAT_SELECTED'? '':"";
	}			
}

/**
 *
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;


unset($_SESSION['result_message']);

		$data = array(
	'TEMPLATE_DIR' 				=>	TEMPLATE_DIR,
	'WB_URL'					=>	WB_URL,
	'PREFERENCES_URL'			=>	PREFERENCES_URL,
	'LOGOUT_URL'				=>	LOGOUT_URL,
	'HEADING_MY_SETTINGS'		=>	$HEADING['MY_SETTINGS'],
	'HEADING_PREFERENCES'		=>	$MENU['PREFERENCES'],
	'TEXT_DISPLAY_NAME'			=>	$TEXT['DISPLAY_NAME'],
	'DISPLAY_NAME'				=>	$wb->get_display_name(),
	'LANG_CODE' 	=>	$langcode,
	'LANG_NAME'		=>	$langname,  
	'TIMEZONE_NAME' 	    =>	$t_name,
	'TIMEZONE_SELECTED'		=>	$t_selected,  
		'DATE_FORMAT_VALUE'	=>	$value,
		'DATE_FORMAT_TITLE'	=>	$title, 
		'TIME_FORMAT_VALUE'	=>	$t_value,
		'TIME_FORMAT_TITLE'	=>	$t_title,       
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
	'AUTH_MIN_LOGIN_LENGTH'		=> AUTH_MIN_LOGIN_LENGTH
		);
		
		echo $parser->render( 
			"preferences_form.lte",	//	template-filename
			$data			//	template-data
		);

?>