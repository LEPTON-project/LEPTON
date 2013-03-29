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


/**
 *	set template file name
 *
 */
$tpl->set_file('login', 'login_form.htt');

/**
 *	Building a secure-hash
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;

$tpl->set_var(array(
	'TEMPLATE_DIR'	=>	TEMPLATE_DIR,
	'WB_URL'		=>	WB_URL,
	'LOGIN_URL'		=>	LOGIN_URL,
	'LOGOUT_URL'	=>	LOGOUT_URL,
	'FORGOT_URL'	=>	FORGOT_URL,  
	'TEXT_USERNAME'	=>	$TEXT['USERNAME'],
	'TEXT_PASSWORD'	=>	$TEXT['PASSWORD'],
	'MESSAGE'		=>	$thisApp->message,  
	'REDIRECT_URL'	=>	$thisApp->redirect_url,   
	'TEXT_LOGIN'	=>	$MENU['LOGIN'],
	'TEXT_LOGOUT'	=>	$MENU['LOGOUT'],
	'TEXT_RESET'	=>	$TEXT['RESET'],
	'HASH'			=>	$hash,
	'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS']
	)
);

unset($_SESSION['result_message']);

// for use in template <!-- BEGIN/END comment_block -->
$tpl->set_block('login', 'comment_block', 'comment_replace'); 
$tpl->set_block('comment_replace', '');

// ouput the final template
$tpl->pparse('output', 'login');
?>