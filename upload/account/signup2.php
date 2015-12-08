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
 * @copyright       2010-2016 LEPTON Project
 * @link            http://www.LEPTON-cms.org
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



function print_error($err_id) {
	header("Location: ".LEPTON_URL."/account/signup.php?err=$err_id");
	exit();
}

// Get details entered
$groups_id = FRONTEND_SIGNUP;
$active = 1;
$username = strtolower(strip_tags($wb->get_post_escaped('username')));
$display_name = strip_tags($wb->get_post_escaped('display_name'));
$mail_to = $wb->get_post('email');

// Check values
if($groups_id == "") {
	print_error(1);
}
if(!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
	print_error(2);
}
if($mail_to != "") {
	if($wb->validate_email($mail_to) == false) {
		print_error(3);
	}
} else {
	print_error(4);
}

$mail_to = addslashes($mail_to);

// Captcha
if(ENABLED_CAPTCHA) {
	if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
		// Check for a mismatch
		if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
			print_error(5);
		}
	} else {
		print_error(5);
	}
}
if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }

// Generate a random password, then update database
require_once( LEPTON_PATH."/framework/functions/function.random_string.php" );
$new_pass = random_string( AUTH_MIN_PASS_LENGTH + mt_rand(0, 4), 'pass' );

$md5_password = md5($new_pass);

// Check if username already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE username = '$username'");
if($results->numRows() > 0) {
	print_error(6);
}

// Check if the email already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE email = '".$mail_to."'");
if($results->numRows() > 0) {
	if(isset($MESSAGE['USERS_EMAIL_TAKEN'])) {
		print_error(7);
	} else {
		print_error(8);
	}
}

// Insert new user into the database
$query = "INSERT INTO ".TABLE_PREFIX."users (group_id,groups_id,active,username,password,display_name,email) VALUES ('$groups_id', '$groups_id', '$active', '$username','$md5_password','$display_name','$mail_to')";
$database->query($query);

if($database->is_error()) {
	// Error updating database
	$message = $database->get_error();
} else {
	// Setup email to send
	$mail_subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];

	// Replace placeholders from language variable with values
	$search = array('{LOGIN_DISPLAY_NAME}', '{LOGIN_WEBSITE_TITLE}', '{LOGIN_NAME}', '{LOGIN_PASSWORD}');
	$replace = array($display_name, WEBSITE_TITLE, $username, $new_pass); 
	$mail_message = str_replace($search, $replace, $MESSAGE['SIGNUP2_BODY_LOGIN_INFO']);

	// Try sending the email
	if($wb->mail(SERVER_EMAIL,$mail_to,$mail_subject,$mail_message)) {    
		// sending copy to admim
		$wb->mail($mail_to, SERVER_EMAIL,$mail_subject,$mail_message);  
		$display_form = false;
//		$wb->print_success($MESSAGE['FORGOT_PASS_PASSWORD_RESET'], LEPTON_URL.'/account/login.php');		
		$_SESSION["signup_message"] = $MESSAGE['FORGOT_PASS_PASSWORD_RESET'];
		header("Location: ".LEPTON_URL."/account/login.php");			
	} else {
		$database->query("DELETE FROM ".TABLE_PREFIX."users WHERE username = '$username'");
		print_error(9);
	}
}

?>