<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 $debug = true;
if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
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


// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $MENU['FORGOT']);
define('MENU_TITLE', $MENU['FORGOT']);
define('VISIBILITY', 'public');


// Set the page content 
if(isset($_POST['hash']) && ($_POST['hash'] != "") ) {
	$confirm = $_POST['hash'];
} else {
	$confirm = NULL;
}

if(isset($_POST['signup']) && ($_POST['signup'] == "1") ) {
	$signup = true;
} else {
	$signup = false;
}


if(isset($_POST['new_password']) && ($_POST['new_password'] != "") ) {
	$new_password = $_POST['new_password'];
} else {
	$new_password = NULL; 
}

if(isset($_POST['new_password2']) && ($_POST['new_password2'] != "") ) {
	$new_password2 = $_POST['new_password2'];
} else {
	$new_password2 = NULL; 
}

//	get user for email
$user = array();
$database->execute_query(
	"SELECT * FROM `".TABLE_PREFIX."users` WHERE login_ip = '".$confirm."' ",
	true,
	$user,
	false
);

if( $new_password != $new_password2 ){
	
	$_SESSION["new_password_message"]= $MESSAGE['PREFERENCES_PASSWORD_MATCH'];
	
} else {
	
	// check if password matches requirements
	if(strlen($new_password)< AUTH_MIN_PASS_LENGTH) {
	
		$_SESSION["new_password_message"]= $MESSAGE['LOGIN_PASSWORD_TOO_SHORT'];	
	
	} elseif (strlen($new_password) > AUTH_MAX_PASS_LENGTH ) {
	
		$_SESSION["new_password_message"]= $MESSAGE['LOGIN_PASSWORD_TOO_LONG'];			
	
	} else {
		// save into database
		$fields = array(
			'login_ip'	=>	$_SERVER['REMOTE_ADDR'],
			'password'	=>	password_hash( $new_password, PASSWORD_DEFAULT),
			'last_reset'=>	time()
		);
		
		$database->build_and_execute( 'UPDATE', TABLE_PREFIX."users", $fields,"login_ip= '".$confirm."'");
													
		if ( $database->is_error() ) {
			// Error updating database
			$_SESSION["new_password_message"] = $database->get_error();
		
		} else {
		
			$_SESSION["new_password_message"] = $MESSAGE['PREFERENCES_PASSWORD_CHANGED'];					
		}
		
		require_once (LEPTON_PATH.'/modules/lib_phpmailer/library.php');
		//send confirmation link to email
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		$mail->CharSet = DEFAULT_CHARSET;	
		//Set who the message is to be sent from
		$mail->setFrom(SERVER_EMAIL);
		//Set who the message is to be sent to
		$mail->addAddress($user['email']);
		//Set the subject line
		$mail->Subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];
		//Switch to TEXT messages
		$mail->IsHTML(true);
		$mail->Body = sprintf($MESSAGE['FORGOT_PASSWORD_SUCCESS'],$user['username']);					
		
		if (!$mail->send()) {
			$_SESSION["new_password_message"] = "Mailer Error: " . $mail->ErrorInfo;
		} else {
				$message = $MESSAGE['FORGOT_PASSWORD_SUCCESS'];			
		}

	}
}

if(!isset($_SESSION["new_password_message"])) {
	define('PAGE_CONTENT', LEPTON_PATH.'/index.php');
} else {
	define('PAGE_CONTENT', LEPTON_PATH.'/account/new_password_form.php');
}

// Set auto authentication to false
$auto_auth = false;

// Include the index (wrapper) file
require(LEPTON_PATH.'/index.php');

?>