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
 * @link            https://www.LEPTON-cms.org
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

/* Include template parser */
if (file_exists(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/index.php')) 
  {
    require_once(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/index.php');
  }

else
  {
    require_once(LEPTON_PATH . '/include/phplib/template.inc');
  }

// see if there exists a template file in "account-htt" folder  inside the current template
require_once( dirname( __FILE__)."/../framework/class.lepton.filemanager.php" );
global $lepton_filemanager;
$template_path = $lepton_filemanager->resolve_path( 
	"login_form.htt",
	'/account/templates/',
	true
);
if ($template_path === NULL) die("Can't find a valid template for this form!");

$tpl = new Template(LEPTON_PATH.$template_path);

$tpl->set_unknowns('remove');


// see if there exists a frontend template file or use the fallback
if (file_exists(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/login_form.php')) 
{
	require_once(LEPTON_PATH.'/templates/'.DEFAULT_TEMPLATE.'/frontend/login/login_form.php');
}
else
{
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
	'LEPTON_URL'		=>	LEPTON_URL,
	'LOGIN_URL'		=>	LOGIN_URL,
	'LOGOUT_URL'	=>	LOGOUT_URL,
	'FORGOT_URL'	=>	FORGOT_URL,  
	'TEXT_USERNAME'	=>	$TEXT['USERNAME'],
	'TEXT_PASSWORD'	=>	$TEXT['PASSWORD'],
	'MESSAGE'		=>	$thisApp->message, 
	'SUCCESS'		=> (isset($_SESSION["signup_message"]) ? "<div class='success_message'>".$_SESSION["signup_message"]."</div>" : ''),  
	'REDIRECT_URL'	=>	$thisApp->redirect_url,   
	'TEXT_LOGIN'	=>	$MENU['LOGIN'],
	'TEXT_LOGOUT'	=>	$MENU['LOGOUT'],
	'TEXT_RESET'	=>	$TEXT['RESET'],
	'HASH'			=>	$hash,
	'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS']
	)
);

if (isset($_SESSION["signup_message"])) unset ($_SESSION["signup_message"]);
if (isset($_SESSION["result_message"])) unset ($_SESSION["result_message"]);

// for use in template <!-- BEGIN/END comment_block -->
$tpl->set_block('login', 'comment_block', 'comment_replace'); 
$tpl->set_block('comment_replace', '');

// ouput the final template
$tpl->pparse('output', 'login');
}
?>