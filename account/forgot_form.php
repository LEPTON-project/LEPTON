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
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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

// Check if the user has already submitted the form, otherwise show it
if(isset($_POST['email']) && $_POST['email'] != "" &&
    preg_match("/([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}/i", $_POST['email'])) {
	$email = strip_tags($_POST['email']);
	
	// Check if the email exists in the database
	$query = "SELECT user_id,username,display_name,email,last_reset,password FROM ".TABLE_PREFIX."users WHERE email = '".$wb->add_slashes($_POST['email'])."'";
	$results = $database->query($query);
	if($results->numRows() > 0) {
	
		// Get the id, username, email, and last_reset from the above db query
		$results_array = $results->fetchRow( MYSQL_ASSOC );
		
		// Check if the password has been reset in the last 2 hours
		$last_reset = $results_array['last_reset'];
		$time_diff = time()-$last_reset; // Time since last reset in seconds
		$time_diff = $time_diff/60/60; // Time since last reset in hours
		if($time_diff < 2) {
			
			// Tell the user that their password cannot be reset more than once per hour
			$message = $MESSAGE['FORGOT_PASS_ALREADY_RESET'];
			
		} else {
		
			$old_pass = $results_array['password'];

			/**
			 *	Generate a random password then update the database with it
			 *
			 */
			$r = array_merge(
				range("a", "z"),
				range(1, 9)
			);
			$r = array_diff($r, array('i', 'l', 'o'));
			for ($i=0; $i < 3; $i++) $r = array_merge($r, $r);
			shuffle($r);
			$new_pass = implode("", array_slice($r, 0, AUTH_MIN_PASS_LENGTH ) );
			
			$database->query("UPDATE ".TABLE_PREFIX."users SET password = '".md5($new_pass)."', last_reset = '".time()."' WHERE user_id = '".$results_array['user_id']."'");
			
			if($database->is_error()) {
				// Error updating database
				$message = $database->get_error();
			} else {
				// Setup email to send
				$mail_to = $email;
				$mail_subject = $MESSAGE['SIGNUP2_SUBJECT_LOGIN_INFO'];

				// Replace placeholders from language variable with values
				$search = array('{LOGIN_DISPLAY_NAME}', '{LOGIN_WEBSITE_TITLE}', '{LOGIN_NAME}', '{LOGIN_PASSWORD}');
				$replace = array($results_array['display_name'], WEBSITE_TITLE, $results_array['username'], $new_pass); 
				$mail_message = str_replace($search, $replace, $MESSAGE['SIGNUP2_BODY_LOGIN_FORGOT']);

				// Try sending the email
				if($wb->mail(SERVER_EMAIL,$mail_to,$mail_subject,$mail_message)) { 
					$message = $MESSAGE['FORGOT_PASS_PASSWORD_RESET'];
					$display_form = false;
				} else {
					$database->query("UPDATE ".TABLE_PREFIX."users SET password = '".$old_pass."' WHERE user_id = '".$results_array['user_id']."'");
					$message = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL'];
				}
			}
		
		}

	} else {
		// Email doesn't exist, so tell the user
		$message = $MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'];
	}
	
} else {
	$email = '';
}

if(!isset($message)) {
	$message = $MESSAGE['FORGOT_PASS_NO_DATA'];
	$message_color = '000000';
} else {
	$message_color = 'FF0000';
}
	
/* Include  phpLib-template parser */
require_once(WB_PATH . '/include/phplib/template.inc');

// see if there exists a template file in "account-htt" folder  inside the current template

require_once( dirname( __FILE__)."/../framework/class.lepton.filemanager.php" );
global $lepton_filemanager;
$template_path = $lepton_filemanager->resolve_path( 
	"forgot_form.htt",
	'/account/htt/',
	true
);

if ($template_path === NULL) die("Can't find a valid template for this form!");

$tpl = new Template(WB_PATH.$template_path);

$tpl->set_unknowns('remove');  

/**
 *	set template file name
 *
 */
$tpl->set_file('forgot', 'forgot_form.htt');

/**
 *
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;

$redirect_url = (isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : "");

$tpl->set_var(array(
	'TEMPLATE_DIR' 		=>	TEMPLATE_DIR,
	'WB_URL'			=>	WB_URL,
	'URL'			    =>	$redirect_url,
	'FORGOT_URL'		=>	FORGOT_URL,  
	'TEXT_FORGOT'		=>	$MENU['FORGOT'],  
	'MESSAGE_COLOR'		=>	$message_color,
	'MESSAGE'		    =>	$message,  
	'TEXT_EMAIL'		=>	$TEXT['EMAIL'],
	'DISPLAY_EMAIL'		=>	$email,   
	'TEXT_SEND_DETAILS'	=>	$TEXT['SEND_DETAILS'],
	'TEXT_LOGOUT'		=>	$MENU['LOGOUT'],
	'TEXT_RESET'		=>	$TEXT['RESET'],
	'HASH'				=>	$hash,
	'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS']
	)
);

unset($_SESSION['result_message']);

$tpl->set_block('forgot', 'comment_block', 'comment_replace'); 
$tpl->set_block('comment_replace', '');

if(!isset($display_form) OR $display_form != false) {

} else {
	$tpl->set_block('forgot', 'form_block', 'form_block_ref');
	$tpl->set_block('form_block_ref', '');
}
// ouput the final template
$tpl->pparse('output', 'forgot');

?>