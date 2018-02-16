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

// prevent users to access url directly
if(!in_array('users',$_SESSION['SYSTEM_PERMISSIONS']))  {
	header("Location: ".ADMIN_URL."");
	exit(0);
}

// enable custom files
//LEPTON_handle::require_alternative('/templates/'.DEFAULT_THEME.'/backend/backend/users/index.php');
if(file_exists(THEME_PATH .'/backend/backend/users/index.php')) {
	require_once (THEME_PATH .'/backend/backend/users/index.php');
	die();
}

// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();

//	Get all users
$all_users = array();
$database->execute_query(
	"SELECT `user_id`,`username`,`display_name` FROM `".TABLE_PREFIX."users` WHERE `user_id` != '1' ORDER BY `display_name`,`username`",
	true,
	$all_users,
	true
);

//	Get all groups
$all_groups = array();
$database->execute_query(
	"SELECT `group_id`,`name` FROM `".TABLE_PREFIX."groups` WHERE `group_id` != '1'",
	true,
	$all_groups,
	true
);

// Add "administrators" to the groups
$all_groups[] = array(
	'group_id' => 1,
	'name'	=> "Administrators"
);
 

//	Generate an unique username field name
if(!function_exists("random_string")) require_once( LEPTON_PATH."/framework/functions/function.random_string.php");
$username_fieldname = 'username_'.random_string(16);

//	Generate a unique hash for the js-ajax-calls
$hash = array(
	'h_name' => random_string(16),
	'h_value' => random_string(24)
);

//	Set the session vars for the hash
$_SESSION['backend_users_h'] = $hash['h_name'];
$_SESSION['backend_users_v'] = $hash['h_value'];


$media_dirs = array();
$skip = LEPTON_PATH.MEDIA_DIRECTORY."/";
directory_list(
	LEPTON_PATH.MEDIA_DIRECTORY,
	false,
	0,
	$media_dirs,
	$skip
);


//	if no User is given, we construct an empty 'default' one for the 'add user' form here:
if (!isset($user)) {
	$user = array(
		'user_id'	=> -1,
		'username'	=> "",
		'display_name'	=> "",
		'email'			=> "",
		'password'		=> "",
		'home_folder'	=> "",
		'time_format'	=> TIME_FORMAT,
		'date_format'	=> DATE_FORMAT,
		'active'		=> 1
	);
}

$page_values = array(
	'all_users'	 => $all_users,
	'all_groups' => $all_groups,
	'media_dirs' => $media_dirs,
	'NEWUSERHINT'=> sprintf($TEXT['NEW_USER_HINT'], AUTH_MIN_LOGIN_LENGTH, AUTH_MIN_PASS_LENGTH),
	'ACTION_URL'=> ADMIN_URL.'/users/save.php',	// 1
	'FORM_NAME'	=> "user_".random_string(16),
	'username_fieldname' => $username_fieldname,
	'user'	=> $user,
	'hash'	=> $hash
);

$oTWIG->registerPath( THEME_PATH."theme","users" );
echo $oTWIG->render(
	"@theme/users.lte",
	$page_values
);
 
$admin->print_footer();
?>