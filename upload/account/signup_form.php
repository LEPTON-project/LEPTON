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



require_once(LEPTON_PATH.'/modules/captcha_control/captcha/captcha.php');

if (isset($_GET['err']) && (int)($_GET['err']) == ($_GET['err'])) {
	$err_msg = '';
	switch ((int)$_GET['err']) {
		case 1: $err_msg = $MESSAGE['USERS_NO_GROUP']; break;
		case 2: $err_msg = $MESSAGE['USERS_NAME_INVALID_CHARS'].' / '.$MESSAGE['USERS_USERNAME_TOO_SHORT']; break;
		case 3: $err_msg = $MESSAGE['USERS_INVALID_EMAIL']; break;
		case 4: $err_msg = $MESSAGE['SIGNUP_NO_EMAIL']; break;
		case 5: $err_msg = $MESSAGE['MOD_FORM_INCORRECT_CAPTCHA']; break;
		case 6: $err_msg = $MESSAGE['USERS_USERNAME_TAKEN']; break;
		case 7: $err_msg = $MESSAGE['USERS_EMAIL_TAKEN']; break;
		case 8: $err_msg = $MESSAGE['USERS_INVALID_EMAIL']; break;
		case 9: $err_msg = $MESSAGE['FORGOT_PASS_CANNOT_EMAIL']; break;
	}
	if ($err_msg != '') {
		echo "<p style='color:red'>$err_msg</p>";
	}
}

/* Include template parser */
require_once(LEPTON_PATH . '/modules/lib_twig/library.php');


// see if there exists a template file in "account" folder
require_once( dirname( __FILE__)."/../framework/class.lepton.filemanager.php" );
global $lepton_filemanager;
$template_path = $lepton_filemanager->resolve_path( 
	"signup_form.lte",
	'/account/templates/',
	true
);
if ($template_path === NULL) die("Can't find a valid template for this form!");


// see if there exists a frontend template file or use the fallback
if (file_exists(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/signup_form.php')) 
{
	require_once(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/signup_form.php');
}
else
{
//initialize twig template engine
global $parser;		// twig parser
global $loader;		// twig file manager
if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

// prependpath to make sure twig is looking in this module template folder first
$loader->prependPath( dirname(__FILE__)."/templates/" );

/**
 *	Build the secure hash
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;

/**
 *	Getting the captha
 *
 */
ob_start();
	call_captcha();
	$captcha = ob_get_clean();

unset($_SESSION['result_message']);

$submitted_when = time();
$_SESSION['submitted_when'] = $submitted_when;

unset($_SESSION['result_message']);

$data = array(
	'TEMPLATE_DIR'	=>	TEMPLATE_DIR,
	'SIGNUP_URL'	=>	SIGNUP_URL,
	'LOGOUT_URL'	=>	LOGOUT_URL,
	'FORGOT_URL'	=>	FORGOT_URL,  
	'TEXT_SIGNUP'	=>	$TEXT['SIGNUP'],
	'TEXT_USERNAME'		=>	$TEXT['USERNAME'],
	'TEXT_DISPLAY_NAME'	=>	$TEXT['DISPLAY_NAME'],
	'TEXT_FULL_NAME'	=>	$TEXT['FULL_NAME'],
	'TEXT_EMAIL'	    =>	$TEXT['EMAIL'],
	'CALL_CAPTCHA'		=>	$captcha,     
	'TEXT_LOGIN'		=>	$MENU['LOGIN'],
	'TEXT_RESET'		=>	$TEXT['RESET'],
	'HASH'				=>	$hash, 
	'TEXT_VERIFICATION' => $TEXT['VERIFICATION'],
	'submitted_when'	=> $submitted_when
);
		
echo $parser->render( 
	"signup_form.lte",	//	template-filename
	$data				//	template-data
);
}
?>