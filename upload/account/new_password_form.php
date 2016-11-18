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

// display success message from save new password
if(isset ($_SESSION["new_password_message"])) {
	echo $_SESSION["new_password_message"];
	unset($_SESSION["new_password_message"]);
	return 0;
}

// create timestamp
$current_time = time();

if(isset($_GET['hash']) && ($_GET['hash'] != "") ) {
	$confirm = $_GET['hash'];
} else {
	$confirm = NULL; 
}

if(isset($_GET['signup']) && ($_GET['signup'] == "1") ) {
	$signup = true;
} else {
	$signup = false; 
}

if ($current_time > ($confirm + 3600)) {
	$message = $MESSAGE['FORGOT_CONFIRM_OLD'];	
	echo($message);
} else {

	//	check if hash is in database
	$user = array();
	$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."users` WHERE login_ip = '".$confirm."' ",
		true,
		$user,
		false
	);
	if(count($user) == 0) {
		// info that hash doesn't exist	
		$message = $MESSAGE['FORGOT_PASS_EMAIL_NOT_FOUND'];
	}	else {

		// initialize twig template engine
		global $parser;		// twig parser
		global $loader;		// twig file manager
		if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
		
		// prependpath to make sure twig is looking in this module template folder first
		$loader->prependPath( dirname(__FILE__)."/templates/" );		
		
		// Frontend-template?
		$temp_look_for_path = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login/templates/";
		if(file_exists(	$temp_look_for_path."new_password_form.lte")) {
			$loader->prependPath( $temp_look_for_path );
		}
			
		 //	Delete any "result_message" if there is one.
		if( true === isset($_SESSION['result_message']) ) unset($_SESSION['result_message']);

		$data = array(
			'TEXT_ENABLE_JAVASCRIPT'	=> $TEXT['ENABLE_JAVASCRIPT'],
			'RESULT_MESSAGE'			=> (isset($_SESSION['result_message'])) ? $_SESSION['result_message'] : "",
			'NEW_PASSWORD_URL'			=>	LEPTON_URL.'/account/save_new_password.php',				
			'TEMPLATE_DIR' 				=>	TEMPLATE_DIR,
			'HASH'						=>	$confirm,
			'r_time'					=>	$current_time,
			'signup'					=>	((true === $signup) ? 1 : 0),	// make sure 'signup' has a valid integer			
			'HEADING_MY_PASSWORD'		=>	$HEADING['MY_PASSWORD'],
			'TEXT_NEW_PASSWORD'			=>	$TEXT['NEW_PASSWORD'],
			'TEXT_RETYPE_NEW_PASSWORD'	=>	$TEXT['RETYPE_NEW_PASSWORD'],
			'TEXT_SAVE'					=>	$TEXT['SAVE'],
			//	Text for passwords are different
			'ERROR_PASS_DOESN_MATCH'	=>	$MESSAGE['PREFERENCES_PASSWORD_MATCH'],
			//	Min- length of passwords
			'AUTH_MIN_PASS_LENGTH'		=> AUTH_MIN_PASS_LENGTH,
			'USERS_PASSWORD_TOO_SHORT' => $MESSAGE['USERS_PASSWORD_TOO_SHORT']							
		);
						
		echo $parser->render( 
			"new_password_form.lte",	//	template-filename
			$data			//	template-data
		);			
	}	
}

?>