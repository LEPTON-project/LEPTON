<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke, LEPTON project
 *  @copyright      2004-2010 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2010-2014 LEPTON project  
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
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



require_once('../../config.php');

// Get id
if(!isset($_GET['submission_id']) OR !is_numeric($_GET['submission_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$submission_id = $_GET['submission_id'];
}

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

// Get submission details
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_submissions WHERE submission_id = '$submission_id'");
$submission = $query_content->fetchRow();

// Get the user details of whoever did this submission
$query_user = "SELECT username,display_name FROM ".TABLE_PREFIX."users WHERE user_id = '".$submission['submitted_by']."'";
$get_user = $database->query($query_user);
if($get_user->numRows() != 0) {
	$user = $get_user->fetchRow();
} else {
	$user['display_name'] = 'Unknown';
	$user['username'] = 'unknown';
}

?>
<div class="container">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td><?php echo $TEXT['SUBMISSION_ID']; ?>:</td>
	<td><?php echo $submission['submission_id']; ?></td>
</tr>
<tr>
	<td><?php echo $TEXT['SUBMITTED']; ?>:</td>
	<td><?php echo date(TIME_FORMAT.', '.DATE_FORMAT, $submission['submitted_when']); ?></td>
</td>
<tr>
	<td><?php echo $TEXT['USER']; ?>:</td>
	<td><?php echo $user['display_name'].' ('.$user['username'].')'; ?></td>
</tr>
<tr>
	<td colspan="2">
		<hr />
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php echo nl2br($submission['body']); ?>
	</td>
</tr>
</table>

<br />

<input type="button" value="<?php echo $TEXT['CLOSE']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 150px; margin-top: 5px;" />
<input class="delete" type="button" value="<?php echo $TEXT['DELETE']; ?>" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo LEPTON_URL; ?>/modules/form/delete_submission.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&submission_id=<?php echo $submission_id; ?>');" style="width: 150px; margin-top: 5px;" />
</div>
<?php

// Print admin footer
$admin->print_footer();

?>