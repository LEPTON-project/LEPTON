<?php

/**
 *  @module         form
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @copyright      2004-2011 Ryan Djurovich, Rudolph Lartey, John Maats, Dietrich Roland Pehlke 
 *  @license        see info.php of this module
 *  @license terms  see info.php of this module
 *  @requirements   PHP 5.2.x and higher
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



// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/form/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/form/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/form/languages/'.LANGUAGE .'.php');
}

// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_settings WHERE section_id = '$section_id'");
$setting = $query_content->fetchRow();

// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/form/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/form/backend.css');
	echo "\n</style>\n";
}

?>
<div class="container">
<h2><?php echo $MOD_FORM['SETTINGS']; ?></h2>
<?php
// include the button to edit the optional module CSS files
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('form');
}
?>

<form name="edit" action="<?php echo WB_URL; ?>/modules/form/save_settings.php" method="post" style="margin: 0;">

<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<div class="container">
<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<tr>
		<td colspan="2"><strong><?php echo $HEADING['GENERAL_SETTINGS']; ?></strong></td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['CAPTCHA_VERIFICATION']; ?>:</td>
		<td>
			<input type="radio" name="use_captcha" id="use_captcha_true" value="1"<?php if($setting['use_captcha'] == true) { echo ' checked="checked"'; } ?> />
			<label for="use_captcha_true"><?php echo $TEXT['ENABLED']; ?></label>
			<input type="radio" name="use_captcha" id="use_captcha_false" value="0"<?php if($setting['use_captcha'] == false) { echo ' checked="checked"'; } ?> />
			<label for="use_captcha_false"><?php echo $TEXT['DISABLED']; ?></label>
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['MAX_SUBMISSIONS_PER_HOUR']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="max_submissions" style="width: 30px;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['max_submissions'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['SUBMISSIONS_STORED_IN_DATABASE']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="stored_submissions" style="width: 30px;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['stored_submissions'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['HEADER']; ?>:</td>
		<td class="setting_value">
			<textarea name="header" cols="80" rows="6" style="width: 98%; height: 80px;"><?php echo ($setting['header']); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['FIELD'].' '.$TEXT['LOOP']; ?>:</td>
		<td class="setting_value">
			<textarea name="field_loop" cols="80" rows="6" style="width: 98%; height: 80px;"><?php echo ($setting['field_loop']); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['FOOTER']; ?>:</td>
		<td class="setting_value">
			<textarea name="footer" cols="80" rows="6" style="width: 98%; height: 80px;"><?php echo str_replace($raw, $friendly, ($setting['footer'])); ?></textarea>
		</td>
	</tr>
</table>	
</div>
<div class="container">
<table cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
	<tr>
		<td colspan="2"><strong><?php echo $TEXT['EMAIL'].' '.$TEXT['SETTINGS']; ?></strong></td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['TO']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="email_to" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['email_to'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['FROM']; ?>:</td>
		<td class="setting_value">
			<select name="email_from_field" style="width: 98%;">
			<option value="" onclick="javascript: document.getElementById('email_from').style.display = 'block';"><?php echo $TEXT['CUSTOM']; ?>:</option>
			<?php
			$email_from_value = str_replace($raw, $friendly, ($setting['email_from']));
			$query_email_fields = $database->query("SELECT field_id,title FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id' AND ( type = 'textfield' OR  type = 'email' ) ORDER BY position ASC");
			if($query_email_fields->numRows() > 0) {
				while($field = $query_email_fields->fetchRow()) {
					?>
					<option value="field<?php echo $field['field_id']; ?>"<?php if($email_from_value == 'field'.$field['field_id']) { echo ' selected'; $selected = true; } ?> onclick="javascript: document.getElementById('email_from').style.display = 'none';">
						<?php echo $TEXT['FIELD'].': '.$field['title']; ?>
					</option>
					<?php
				}
			}
			?>
			</select>
			<input type="text" name="email_from" id="email_from" style="width: 98%; display: <?php if(isset($selected) AND $selected == true) { echo 'none'; } else { echo 'block'; } ?>;" maxlength="255" value="<?php if(substr($email_from_value, 0, 5) != 'field') { echo $email_from_value; } ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['NAME']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="email_fromname" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['email_fromname'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['SUBJECT']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="email_subject" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['email_subject'])); ?>" />
		</td>
	</tr>
</table>	
</div>
<div class="container">
<table cellpadding="2" cellspacing="0" border="0" width="100%" style="margin-top: 3px;">
	<tr>
		<td colspan="2"><strong><?php echo $TEXT['SUCCESS'].' '.$TEXT['SETTINGS']; ?></strong></td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['TO']; ?>:</td>
		<td class="setting_value">
			<select name="success_email_to" style="width: 98%;">
			<option value="" onclick="javascript: document.getElementById('success_email_to').style.display = 'block';"><?php echo $TEXT['NONE']; ?></option>
			<?php
			$success_email_to = str_replace($raw, $friendly, ($setting['success_email_to']));
			$query_email_fields = $database->query("SELECT field_id,title FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id' AND ( type = 'textfield' OR  type = 'email' ) ORDER BY position ASC");
			if($query_email_fields->numRows() > 0) {
				while($field = $query_email_fields->fetchRow()) {
					?>
					<option value="field<?php echo $field['field_id']; ?>"<?php if($success_email_to == 'field'.$field['field_id']) { echo ' selected'; $selected = true; } ?> onclick="javascript: document.getElementById('email_from').style.display = 'none';">
						<?php echo $TEXT['FIELD'].': '.$field['title']; ?>
					</option>
					<?php
				}
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['FROM']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="success_email_from" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['success_email_from'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['NAME']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="success_email_fromname" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['success_email_fromname'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['SUBJECT']; ?>:</td>
		<td class="setting_value">
			<input type="text" name="success_email_subject" style="width: 98%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['success_email_subject'])); ?>" />
		</td>
	</tr>
	<tr>
		<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['TEXT']; ?>:</td>
		<td class="setting_value">
			<textarea name="success_email_text" cols="80" rows="1" style="width: 98%; height: 80px;"><?php echo str_replace($raw, $friendly, ($setting['success_email_text'])); ?></textarea>
		</td>
	</tr>
	<tr>
		<td class="newsection"><?php echo $TEXT['SUCCESS'].' '.$TEXT['PAGE']; ?>:</td>
		<td class="newsection">
			<select name="success_page">
			<option value="none"><?php echo $TEXT['NONE']; ?></option>
			<?php 
			// Get exisiting pages and show the pagenames
			$query = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE visibility <> 'deleted'");
			while($mail_page = $query->fetchRow()) {
				if(!$admin->page_is_visible($mail_page))
					continue;
				$mail_pagename = $mail_page['menu_title'];		
				$success_page = $mail_page['page_id'];
			  //	echo $success_page.':'.$setting['success_page'].':'; not vailde
				if($setting['success_page'] == $success_page) {
					$selected = ' selected="selected"';
				} else { 
					$selected = '';
				}
				echo '<option value="'.$success_page.'"'.$selected.'>'.$mail_pagename.'</option>';
		 	}
			?>
			</select>
		</td>
	</tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;">
			<input class="reset" type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>
</form>
</div>
<?php

// Print admin footer
$admin->print_footer();

?>