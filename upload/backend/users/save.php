<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
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


$admin = new LEPTON_admin('Access', 'users_modify');

// Check if user id is a valid number and doesnt equal 1
if(!isset($_POST['user_id']) OR !is_numeric($_POST['user_id']) OR $_POST['user_id'] == 1) {
	header("Location: index.php");
	exit(0);
} else {
	$user_id = $_POST['user_id'];
}

// check if job save or delete
if (isset($_POST['job'])) {
	if ($_POST['job'] == "delete") {
	
		$database->query("DELETE FROM `".TABLE_PREFIX."users` WHERE `user_id` = '".$user_id."' LIMIT 1");
	
		if($database->is_error())
		{
			$admin->print_error($database->get_error());
		}
		else {
			$admin->print_success($MESSAGE['USERS_DELETED'], ADMIN_URL.'/users/index.php');	
		}			
		$admin->print_footer();
		return true;
	}
}


// Gather details entered
$groups_id = (isset($_POST['groups'])) ? implode(",", $_POST['groups'] ) : '';
$active = addslashes($_POST['active'][0]);
$username_fieldname = str_replace(array("[[", "]]"), '', htmlspecialchars($admin->get_post('username_fieldname'), ENT_QUOTES));
$username = $admin->get_post_escaped( $username_fieldname );

$password = $admin->get_post('password');
$password2 = $admin->get_post('password2');
$display_name = str_replace(array("[[", "]]"), '', htmlspecialchars($admin->get_post('display_name'), ENT_QUOTES));

$email = $admin->get_post_escaped('email');
$home_folder = $admin->get_post_escaped('home_folder');

// Check values
if($groups_id == "") {
	$admin->print_error($MESSAGE['USERS_NO_GROUP'],'index.php');
}

if (strlen( $username ) < 3) {
	$admin->print_error( $MESSAGE['USERS_USERNAME_TOO_SHORT']." [1]", 'index.php');
}

if(!preg_match('/^[a-z]{1}[a-z0-9@\._-]{2,}$/i', $username)) {
	$admin->print_error( $MESSAGE['USERS_NAME_INVALID_CHARS'], 'index.php' );
}

if( ($password != "") && ($password2 != "") ) {

	if(strlen($password) < AUTH_MIN_PASS_LENGTH) {
		$admin->print_error($MESSAGE['USERS_PASSWORD_TOO_SHORT']." [2]",'index.php');
	}
	if($password != $password2) {
		$admin->print_error($MESSAGE['USERS_PASSWORD_MISMATCH'],'index.php');
	}
}

if($email != "")
{
	if( false == filter_var( $email, FILTER_VALIDATE_EMAIL ) )
    {
        $admin->print_error($MESSAGE['USERS_INVALID_EMAIL'],'index.php');
	}
} else {
	/**
	 *	e-mail must be present
	 *
	 */
	$admin->print_error($MESSAGE['SIGNUP_NO_EMAIL'],'index.php');
}

/**
 *	Check if the email already exists
 *
 */
$results = $database->query("SELECT `user_id` FROM `".TABLE_PREFIX."users` WHERE `email` = '".addslashes($_POST['email'])."' AND `user_id` <> '".$user_id."' ");
if($results->numRows() > 0)
{
	if(isset($MESSAGE['USERS_EMAIL_TAKEN']))
    {
		$admin->print_error($MESSAGE['USERS_EMAIL_TAKEN'],'index.php');
	} else {
		$admin->print_error($MESSAGE['USERS_INVALID_EMAIL'],'index.php');
	}
}



/**
 *	Update the database
 *
 */
$fields = array(
	'groups_id'		=> $groups_id,
	'active'		=> $active,
	'display_name'	=> $display_name,
	'home_folder'	=> $home_folder,
	'email'	=> $email
);

if( $password2 != "") $fields['password'] = password_hash( $password , PASSWORD_DEFAULT);	

/**
 *	Prevent from renaming user to "admin"
 *
 */
if ($username != 'admin') $fields[ 'username' ] = $username;

$query = $database->build_mysql_query(
	'UPDATE',
	TABLE_PREFIX."users",
	$fields,
	"`user_id`='".$user_id."'"
);

$database->query($query);

if($database->is_error()) {
	$admin->print_error($database->get_error(),'index.php');
} else {
	$admin->print_success($MESSAGE['USERS_SAVED']);
}

/**
 *	Print admin footer
 *
 */
$admin->print_footer();

?>