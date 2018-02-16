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


// enable custom files
//LEPTON_handle::require_alternative('/templates/'.DEFAULT_THEME.'/backend/backend/preferences/index.php');
  if(file_exists(LEPTON_PATH.'/templates/'.DEFAULT_THEME.'/backend/backend/preferences/index.php')) {
      require_once LEPTON_PATH.'/templates/'.DEFAULT_THEME.'/backend/backend/preferences/index.php';
      die();
  }

// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();


//	Initial page addition
require_once(LEPTON_PATH."/modules/initial_page/classes/class.init_page.php");
$ref = new class_init_page( $database );
$info = $ref->get_user_info( $_SESSION['USER_ID'] );
	
$options = array(
	'pages'			=> true,
	'tools'			=> ($_SESSION['GROUP_ID'] == 1) ? true : false,
	'backend_pages' => ($_SESSION['GROUP_ID'] == 1) ? true : false
);
	
$select = $ref->get_single_user_select( $_SESSION['USER_ID'], 'init_page_select', $info['init_page'], $options);
	
$initial_page_language = $ref->get_language();
	

//	read user-info from table users
$current_user = array();
$database->execute_query(
	'SELECT * FROM `'.TABLE_PREFIX.'users` WHERE `user_id` = '.(int)$admin->get_user_id().' ',	
	true,
	$current_user,
	false
);


// read available languages from table addons
$languages = array();
$database->execute_query(
	'SELECT `directory`,`name` FROM `'.TABLE_PREFIX.'addons` WHERE `type` = "language" ORDER BY `directory`',
	true,
	$languages,
	true
);

$page_values = array(
	'current_user'	=> $current_user,
	'languages'		=> $languages,
	'LANGUAGE'		=> LANGUAGE,
	'timezone_table'	=> LEPTON_basics::get_timezones(),
	'timezone'			=> isset( $_SESSION[ 'TIMEZONE_STRING' ] ) ? $_SESSION[ 'TIMEZONE_STRING' ] : DEFAULT_TIMEZONESTRING,
	'TIME_FORMATS'		=> LEPTON_basics::get_timeformats(),
	'TIME_FORMAT'		=> TIME_FORMAT,
	'DATE_FORMATS'		=> LEPTON_basics::get_dateformats(),
	'DATE_FORMAT'		=> DATE_FORMAT,
	'EMPTY_STRING'		=> '',
	'JS_TEXT_NEED_PASSWORD_TO_CONFIRM' => js_alert_encode($TEXT['NEED_PASSWORD_TO_CONFIRM']),
	'ACTION_URL' => ADMIN_URL.'/preferences/save.php',
	'FORM_NAME' => 'preferences_save',

// Initial Page addition

	'INIT_PAGE_SELECT' => $select,
	'INIT_PAGE_LABEL' => $initial_page_language['label_default']
);
	


$oTWIG->registerPath( THEME_PATH."/templates","theme" );
echo $oTWIG->render(
	"@theme/preferences.lte",
	$page_values
);
 
$admin->print_footer();

?>