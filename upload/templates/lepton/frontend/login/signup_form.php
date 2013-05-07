<?php

/**
 *  @template       Lepton-Start
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2010-2013 CMS-LAB
 *  @license        http://creativecommons.org/licenses/by/3.0/
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
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



/**
 *	set template file name
 *
 */
$tpl->set_file('signup', 'signup_form.htt');

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
	
$tpl->set_var(array(
	'TEMPLATE_DIR'	=>	TEMPLATE_DIR,
	'WB_URL'		=>	WB_URL,
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
	'TEXT_VERIFICATION' => $TEXT['VERIFICATION']
	)
);

unset($_SESSION['result_message']);

// for use in template <!-- BEGIN/END comment_block -->
$tpl->set_block('signup', 'comment_block', 'comment_replace'); 
$tpl->set_block('comment_replace', '');

if (!defined(ENABLED_ASP)) {
	$tpl->set_block('asp', 'asp_block', 'asp_replace'); 
	$tpl->set_block('asp_replace', '');
}

if (!defined(ENABLED_CAPTCHA)) {
	$tpl->set_block('captcha', 'captcha_block', 'captcha_replace'); 
	$tpl->set_block('captcha_replace', '');
}

// ouput the final template
$tpl->pparse('output', 'signup');

?>