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

if (!isset($_SESSION['backend_users_h'])) die();
if (!isset($_SESSION['backend_users_v'])) die();

if( $_POST[ $_SESSION['backend_users_h'] ] != $_SESSION['backend_users_v']) die();

#unset($_SESSION['backend_users_h']);
#unset($_SESSION['backend_users_v']);

if (!isset($_POST['id'])) die();

$uid = intval($_POST['id']);
if ($uid == 0) die();

// echo "post ist get ok -> ".$_POST['id'];

$u_info = array();
$database->execute_query(
	"SELECT `groups_id`,`active`,`username`,`display_name`,`home_folder`,`email` from `". TABLE_PREFIX."users` WHERE `user_id`=".$uid,
	true,
	$u_info,
	false
);

echo json_encode($u_info);

?>