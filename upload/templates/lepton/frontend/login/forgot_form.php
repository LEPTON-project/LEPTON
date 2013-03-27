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