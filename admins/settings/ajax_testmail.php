<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 * @version         $Id: ajax_testmail.php 1238 2011-10-21 12:12:40Z frankh $
 *
 */

ob_start();

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

global $TEXT;

header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header( "Content-Type: text/html; charset:utf-8;" );

// not needed, config is loaded with class.secure
// include realpath(dirname(__FILE__)).'/../../config.php';
include realpath(dirname(__FILE__)).'/../../framework/class.admin.php';
$admin = new admin('Settings', 'settings_basic');

$curr_user_is_admin = ( in_array(1, $admin->get_groups_id()) );

if ( ! $curr_user_is_admin ) {
    echo "<div style='border: 2px solid #CC0000; padding: 5px; text-align: center; background-color: #ffbaba;'>You're not allowed to use this function!</div>";
    exit;
}

$settings = array();
$sql      = 'SELECT `name`, `value` FROM `'.TABLE_PREFIX.'settings`';
if ( $res_settings = $database->query( $sql ) ) {
    while ($row = $res_settings->fetchRow( )) {
        $settings[ strtoupper($row['name']) ] = ( $row['name'] != 'wbmailer_smtp_password' ) ? htmlspecialchars($row['value']) : $row['value'];
	}
}
ob_clean();

// send mail
if( $admin->mail( $settings['SERVER_EMAIL'], $settings['SERVER_EMAIL'], 'LEPTON PHP MAILER', $TEXT['WBMAILER_TESTMAIL_TEXT'] ) ) {
    echo "<div style='border: 2px solid #006600; padding: 5px; text-align: center; background-color: #dff2bf;'>", $TEXT['WBMAILER_TESTMAIL_SUCCESS'], "</div>";
}
else {
    $message = ob_get_clean();
    echo "<div style='border: 2px solid #CC0000; padding: 5px; text-align: center; background-color: #ffbaba;'>", $TEXT['WBMAILER_TESTMAIL_FAILED'], "<br />$message<br /></div>";
}

?>