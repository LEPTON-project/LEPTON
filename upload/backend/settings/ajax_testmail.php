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

ob_start();

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

global $TEXT;

header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
header( "Content-Type: text/html; charset:utf-8;" );

require_once (LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Settings', 'settings');

$curr_user_is_admin = ( in_array(1, $admin->get_groups_id()) );

if ( ! $curr_user_is_admin ) {
    echo "<div class='ui negative  message'>You're not allowed to use this function!</div>";
    exit;
}

$settings = array();
$sql      = 'SELECT `name`, `value` FROM `'.TABLE_PREFIX.'settings`';
if ( $res_settings = $database->query( $sql ) ) {
    while ($row = $res_settings->fetchRow( )) {
        $settings[ strtoupper($row['name']) ] = ( $row['name'] != 'mailer_smtp_password' ) ? htmlspecialchars($row['value']) : $row['value'];
	}
}
ob_clean();

// send mail
$mail = new LEPTON_mailer;
$mail->setFrom(SERVER_EMAIL, 'System');	
$mail->addAddress(SERVER_EMAIL, 'System');
$mail->Subject = 'LEPTON PHP MAILER';
$mail->msgHTML($TEXT['MAILER_TESTMAIL_TEXT']);

if (!$mail->send()) {
	 echo "<div class='ui negative  message'>".$TEXT['MAILER_TESTMAIL_FAILED']."<br /> ".$mail->ErrorInfo."<br /></div>";
	
} else {
    echo "<div class='ui positive message'>".$TEXT['MAILER_TESTMAIL_SUCCESS']."</div>";
}

?>