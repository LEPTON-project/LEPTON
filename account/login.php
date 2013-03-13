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

// Make sure the login is enabled
if(!FRONTEND_LOGIN) {
	if(INTRO_PAGE) {
		header('Location: '.WB_URL.PAGES_DIRECTORY.'/index.php');
		exit(0);
	} else {
		header('Location: '.WB_URL.'/index.php');
		exit(0);
	}
}

// Required page details
$page_id = 0;
$page_description = '';
$page_keywords = '';
define('PAGE_ID', 0);
define('ROOT_PARENT', 0);
define('PARENT', 0);
define('LEVEL', 0);
define('PAGE_TITLE', $TEXT['PLEASE_LOGIN']);
define('MENU_TITLE', $TEXT['PLEASE_LOGIN']);
define('VISIBILITY', 'public');
// Set the page content include file
define('PAGE_CONTENT', WB_PATH.'/account/login_form.php');

require_once(WB_PATH.'/framework/class.login.php');

// Create new login app
$redirect = strip_tags((isset($_POST['redirect'])) ? $_POST['redirect'] : ((isset($_GET['redirect'])) ? $_GET['redirect'] : ''));

$thisApp = new Login( array(
	"MAX_ATTEMPS" => MAX_ATTEMPTS,
	"WARNING_URL" => THEME_URL."/templates/warning.html",
  "USERNAME_FIELDNAME" => $TEXT['USERNAME'],
  "PASSWORD_FIELDNAME" => $TEXT['PASSWORD'],
	"MIN_USERNAME_LEN" => AUTH_MIN_LOGIN_LENGTH,
	"MIN_PASSWORD_LEN" => AUTH_MIN_PASS_LENGTH,
	"MAX_USERNAME_LEN" => AUTH_MAX_LOGIN_LENGTH,
	"MAX_PASSWORD_LEN" => AUTH_MAX_PASS_LENGTH,
	"LOGIN_URL" => WB_URL."/account/login.php?redirect=" .$redirect,
	"DEFAULT_URL" => WB_URL.PAGES_DIRECTORY."/index.php",
	"TEMPLATE_DIR" => THEME_PATH."/templates",
	"TEMPLATE_FILE" => "login.htt",
	"FRONTEND" => true,
	"FORGOTTEN_DETAILS_APP" => WB_URL."/account/forgot.php",
	"USERS_TABLE" => TABLE_PREFIX."users",
	"GROUPS_TABLE" => TABLE_PREFIX."groups",
	"REDIRECT_URL" => $redirect
));

// Set extra outsider var
$globals[] = 'thisApp';

// Include the index (wrapper) file
require(WB_PATH.'/index.php');

?>