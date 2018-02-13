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

if(!isset($_POST['modify']) OR !is_numeric($_POST['user_id']) OR $_POST['user_id'] == 1) {
	header("Location: index.php");
	exit(0);
}

// Set parameter 'action' as alternative to javascript mechanism
if(isset($_POST['modify']))
	$_POST['action'] = "modify";


// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();

//	Get all users
$current_user = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."users` WHERE `user_id` = ".$_POST['user_id']." ORDER BY `display_name`,`username`",
	true,
	$current_user,
	false
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

// get names of groups for current_user
$user_groups = array();
$groups_names = explode(",",$current_user['groups_id']);
foreach ($groups_names as $group_temp) {
	 $temp_name = $database->get_one("SELECT `name` FROM `".TABLE_PREFIX."groups` WHERE `group_id` = ".$group_temp."" );
	 $user_groups[$group_temp] =  $temp_name;
}


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
echo(LEPTON_tools::display($user_groups,'pre','ui message'));
echo(LEPTON_tools::display($current_user,'pre','ui message'));
$page_values = array(
	'alternative_url'	=> THEME_URL."/backend/backend/users/",
	'action_url'	=> ADMIN_URL."/users/",	
	'perm_modify'	=> $admin->get_permission('users_modify'),
	'perm_delete'	=> $admin->get_permission('users_delete'),
	'perm_add'		=> $admin->get_permission('users_add'),	
	'all_groups' => $all_groups,
	'user_groups' => $user_groups,
	'media_dirs' => $media_dirs,
	'form_name'	=> "user_".random_string(16),
	'username_fieldname' => $username_fieldname,
	'current_user'	=> $current_user,
	'hash'	=> $hash
);

$oTWIG->registerPath( THEME_PATH."theme","users_modify" );
echo $oTWIG->render(
	"@theme/users_modify.lte",
	$page_values
);
 
$admin->print_footer();

?>