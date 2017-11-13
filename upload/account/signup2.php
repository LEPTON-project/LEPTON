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

function print_error($err_id) {
	header("Location: ".LEPTON_URL."/account/signup.php?err=$err_id");
	exit();
}

// Get details entered
$groups_id = FRONTEND_SIGNUP;
$active = 1;
$username = strtolower(strip_tags($oLEPTON->get_post_escaped('username')));
$display_name = strip_tags($oLEPTON->get_post_escaped('display_name'));
$mail_to = $oLEPTON->get_post('email');

// Check values
if($groups_id == "") {
	print_error(1);
}
if(!preg_match('/^[a-z]{1}[a-z0-9_-]{2,}$/i', $username)) {
	print_error(2);
}
if($mail_to != "") {
	if( false == filter_var( $mail_to, FILTER_VALIDATE_EMAIL) ) {
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

// Delete not confirmed entries. older than 1 hour
$database->simple_query("DELETE from `".TABLE_PREFIX."users` where (`password` = 'unconfirmed signup') AND (`login_ip` < ( UNIX_TIMESTAMP() - 3600 ))");
if($database->is_error()) die($database->get_error());

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

// insert new user and send confirmation link
require_once (LEPTON_PATH.'/modules/lib_phpmailer/library.php');
// create hash 
$confirm_hash = time();

// create confirmation link
$enter_pw_link = LEPTON_URL.'/account/new_password.php?hash='.$confirm_hash.'&signup=1';

//save into database
$fields = array(
	'login_ip'	=>	$confirm_hash,
	'group_id'	=>	$groups_id,
	'groups_id'	=>	$groups_id,
	'active'	=>	$active,
	'username'	=>	$username,
	'password'	=>	'unconfirmed signup',
	'display_name'	=>	$display_name,
	'email'		=>	$mail_to	
	);
$database->build_and_execute( 'INSERT', TABLE_PREFIX."users",$fields);
											
if ( $database->is_error() ) {
// Error updating database
	$message = $database->get_error();
} else {
	//send confirmation link to email
	//Create a new PHPMailer instance
	require_once LEPTON_PATH."/modules/lib_phpmailer/phpmailer/src/PHPMailer.php";
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	$mail->CharSet = DEFAULT_CHARSET;	
	//Set who the message is to be sent from
	$mail->setFrom(SERVER_EMAIL);
	//Set who the message is to be sent to
	$mail->addAddress($mail_to);					
	//Set the subject line
	$mail->Subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];
	//Switch to TEXT messages
	$mail->IsHTML(true);
	// Replace placeholders from language variable with values
	$values = array(
		"\n"	=> "<br />",
		'{LOGIN_DISPLAY_NAME}'	 =>  $display_name,
		'{LOGIN_WEBSITE_TITLE}'	 =>  WEBSITE_TITLE,
		'{ENTER_PW_LINK}'	 	=>  $enter_pw_link
	);
	$mail_message = str_replace( array_keys($values), array_values($values),$MESSAGE['SIGNUP2_BODY_LOGIN_INFO']);
	$mail->Body = $mail_message;	

	//send the message, check for errors
	if (!$mail->send()) {
		$message = "Mailer Error: " . $mail->ErrorInfo;
		$database->query("DELETE FROM ".TABLE_PREFIX."users WHERE username = '$username'");
		print_error(9);
	}	
	$message = $MESSAGE['FORGOT_PASS_PASSWORD_RESET'];	
//	$_SESSION["new_password_message"] = $message;
	
	// Send info to admin
	$mail = new PHPMailer\PHPMailer\PHPMailer;
	$mail->CharSet = DEFAULT_CHARSET;	
	//Set who the message is to be sent from
	$mail->setFrom(SERVER_EMAIL);
	//Set who the message is to be sent to
	$mail->addAddress(SERVER_EMAIL);					
	//Set the subject line
	$mail->Subject = $MESSAGE['SIGNUP2_ADMIN_SUBJECT'];
	//Switch to TEXT messages
	$mail->IsHTML(true);
	// Replace placeholders from language variable with values
	$values = array(
		"\n"	=> "<br />",
		'{LOGIN_NAME}'	 =>  $display_name,
		'{LOGIN_ID}'	 =>  $database->get_one('SELECT LAST_INSERT_ID()'),
		'{LOGIN_EMAIL}'	 =>  $mail_to,
		'{LOGIN_IP}'	 =>  $_SERVER['REMOTE_ADDR'],
		'{SIGNUP_DATE}'	 =>  date("Y.m.d H:i:s")	
	);
	$mail_message = str_replace( array_keys($values), array_values($values),$MESSAGE['SIGNUP2_ADMIN_INFO']);
	$mail->Body = $mail_message;		

	//send the message, check for errors
	$mail->send();	
}
echo $message;
?>