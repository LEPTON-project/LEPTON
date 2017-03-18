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

LEPTON_tools::load(
	LEPTON_PATH.'/languages/'.DEFAULT_LANGUAGE.'.php',
	LEPTON_PATH.'/framework/class.admin.php',
	LEPTON_PATH.'/modules/lib_phpmailer/library.php'
);

$admin = new admin('Start', 'start', false, false);

// Get the website title
$website_title = $database->get_one("SELECT `value` FROM `".TABLE_PREFIX."settings` WHERE `name` = 'title'");

$message = '';

// create hash 
$confirm_hash = time();

// create confirmation link
$enter_pw_link = LEPTON_URL.'/account/new_password.php?hash='.$confirm_hash;

// Check if the user has already submitted the form, otherwise show it
if(isset($_POST['email']) && $_POST['email'] != "" && preg_match("/([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}/i", $_POST['email'])) {
	$email = strip_tags($_POST['email']);

	//	check if mail is in database
	$subscriber = array();
	$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."users` WHERE email = '".$email."' ",
		true,
		$subscriber,
		false
	);


	if(count($subscriber) == 0) {
		// info that email doesn't exist	
		$message = $MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'];
		
	} else {
		// Check if the password has been reset in the last 2 hours
		$last_reset = $subscriber['login_ip'];
		$time_diff = time()-$last_reset; // Time since last reset in seconds
		$time_diff = $time_diff/60/60; // Time since last reset in hours
		if($time_diff < 2) {
						
			// Tell the user that their password cannot be reset more than once per hour
			$message = $MESSAGE['FORGOT_PASS_ALREADY_RESET'];	
			
		} else {		
			//send confirmation link to email
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			$mail->CharSet = DEFAULT_CHARSET;	
			//Set who the message is to be sent from
			$mail->setFrom(SERVER_EMAIL);
			//Set who the message is to be sent to
			$mail->addAddress($email);
			//Set the subject line
			$mail->Subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];
			//Switch to TEXT messages
			$mail->IsHTML(true);
			$mail->Body = sprintf($MESSAGE['FORGOT_PASS_PASSWORD_CONFIRM'],$enter_pw_link,$enter_pw_link);	

			//send the message, check for errors
			if (!$mail->send()) {
				$message = "Mailer Error: " . $mail->ErrorInfo;
			} else {
				//save into database
				$fields = array(
					'login_ip'=>	$confirm_hash
				);
				$database->build_and_execute( 'UPDATE', TABLE_PREFIX."users", $fields,"email = '".$email."'");
												
				if ( $database->is_error() ) {
					// Error updating database
					$message = $database->get_error();
				}
				
				$message = $MESSAGE['FORGOT_PASS_PASSWORD_RESET'];						
			
			}
		}
	}
}				

if(!isset($message)) {
	$message = $MESSAGE['FORGOT_PASS_NO_DATA'];
	$message_color = '000000';
} else {
	$message_color = 'FF0000';
}

$page_values = array(
	'SECTION_FORGOT' => $MENU['FORGOT'],
	'MESSAGE_COLOR' => $message_color,
	'MESSAGE' => $message,
	'LEPTON_URL' => LEPTON_URL,
	'ADMIN_URL' => ADMIN_URL,
	'THEME_URL' => THEME_URL,
	'VERSION' => VERSION,
	'LANGUAGE' => strtolower(LANGUAGE),
	'TEXT_EMAIL' => $TEXT['EMAIL_ADDRESS'],
	'TEXT_SEND_DETAILS' => $TEXT['SEND_DETAILS'],
	'TEXT_HOME' => $TEXT['HOME'],
	'TEXT_NEED_TO_LOGIN' => $TEXT['NEED_TO_LOGIN']
);

$loader->prependPath( THEME_PATH.'/templates' );

echo $parser->render(
	"login_forgot.lte",
	$page_values
);

?>