<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / erpe
 * @link			http://www.cms-lab.com
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        LEPTON 2.x
 * @home			http://www.cms-lab.com
 * @version         see info.php
 *
 *
 */


require('../../config.php');
require_once (WB_PATH.'/framework/summary.functions.php');
require(WB_PATH.'/modules/admin.php');
$update_when_modified = true; 

if(isset($_POST['section_id'])) {
	$email = addslashes(strip_tags($_POST['email']));
	$subject = addslashes(strip_tags($_POST['subject']));
	$template = addslashes(strip_tags($_POST['template']));
	$success = intval($_POST['successpage']);
	
	$query = "UPDATE ".TABLE_PREFIX."mod_miniform SET 
			`email` = '$email', 
			`subject` = '$subject', 
			`successpage` = '$success', 
			`template` = '$template' 
			WHERE `section_id` = '$section_id'";
	$database->query($query);	
}

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>