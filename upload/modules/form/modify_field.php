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



// Get id
if(!isset($_GET['field_id']) OR !is_numeric($_GET['field_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
	exit(0);
} else {
	$field_id = $_GET['field_id'];
}

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_fields WHERE field_id = '$field_id'");
$form = $query_content->fetchRow();
$type = $form['type'];
if($type == '') {
	$type = 'none';
}

// Set raw html <'s and >'s to be replaced by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');
?>
<div class="container">
	
<form name="modify" action="<?php echo LEPTON_URL; ?>/modules/form/save_field.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="field_id" value="<?php echo $field_id; ?>" />

<table cellpadding="2" cellspacing="0" border="0" width="50%">
	<tr>
		<td colspan="2"><strong><?php echo (($type == 'none') ? $TEXT['ADD'] : $TEXT['MODIFY']).' '.$TEXT['FIELD']; ?></strong></td>
	</tr>
	<tr>
		<td width="20%"><?php echo $TEXT['TITLE']; ?>:</td>
		<td>
			<input type="text" name="title" value="<?php echo htmlspecialchars(($form['title'])); ?>" style="width: 98%;" maxlength="255" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['TYPE']; ?>:</td>
		<td>
			<select name="type" style="width: 98%;">
				<option value=""><?php echo $TEXT['PLEASE_SELECT']; ?>...</option>
				<option value="heading"<?php if($type == 'heading') { echo ' selected="selected"'; } ?>><?php echo $TEXT['HEADING']; ?></option>
				<option value="textfield"<?php if($type == 'textfield') { echo ' selected="selected"'; } ?>><?php echo $TEXT['SHORT'].' '.$TEXT['TEXT']; ?> (Textfield)</option>
				<option value="textarea"<?php if($type == 'textarea') { echo ' selected="selected"'; } ?>><?php echo $TEXT['LONG'].' '.$TEXT['TEXT']; ?> (Textarea)</option>
				<option value="select"<?php if($type == 'select') { echo ' selected="selected"'; } ?>><?php echo $TEXT['SELECT_BOX']; ?></option>
				<option value="checkbox"<?php if($type == 'checkbox') { echo ' selected="selected"'; } ?>><?php echo $TEXT['CHECKBOX_GROUP']; ?></option>
				<option value="radio"<?php if($type == 'radio') { echo ' selected="selected"'; } ?>><?php echo $TEXT['RADIO_BUTTON_GROUP']; ?></option>
				<option value="email"<?php if($type == 'email') { echo ' selected="selected"'; } ?>><?php echo $TEXT['EMAIL_ADDRESS']; ?></option>
			</select>
		</td>
	</tr>
<?php if($type != 'none' AND $type != 'email') { ?>
	<?php if($type == 'heading') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['TEMPLATE']; ?>:</td>
		<td>
			<textarea name="template" style="width: 98%; height: 20px;"><?php echo htmlspecialchars(($form['extra'])); ?></textarea>
		</td>
	</tr>
	<?php } elseif($type == 'textfield') { ?>
	<tr>
		<td><?php echo $TEXT['LENGTH']; ?>:</td>
		<td>
			<input type="text" name="length" value="<?php echo $form['extra']; ?>" style="width: 98%;" maxlength="3" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['DEFAULT_TEXT']; ?>:</td>
		<td>
			<input type="text" name="value" value="<?php echo $form['value']; ?>" style="width: 98%;" />
		</td>
	</tr>
	<?php } elseif($type == 'textarea') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['DEFAULT_TEXT']; ?>:</td>
		<td>
			<textarea name="value" style="width: 98%; height: 100px;"><?php echo $form['value']; ?></textarea>
		</td>
	</tr>
	<?php } elseif($type == 'select' OR $type = 'radio' OR $type = 'checkbox') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['LIST_OPTIONS']; ?>:</td>
		<td>
			<?php
			$option_count = 0;
			$list = explode(',', $form['value']);
			foreach($list AS $option_value) {
				$option_count = $option_count+1;
				?>
				<table cellpadding="3" cellspacing="0" width="100%" border="0">
				<tr>
					<td width="70"><?php echo $TEXT['OPTION'].' '.$option_count; ?>:</td>
					<td>
						<input type="text" name="value<?php echo $option_count; ?>" value="<?php echo $option_value; ?>" style="width: 250px;" />
					</td>
				</tr>
				</table>
				<?php
			}
			for($i = 0; $i < 2; $i++) {
				$option_count = $option_count+1;
				?>
				<table cellpadding="3" cellspacing="0" width="100%" border="0">
				<tr>
					<td width="70"><?php echo $TEXT['OPTION'].' '.$option_count; ?>:</td>
					<td>
						<input type="text" name="value<?php echo $option_count; ?>" value="" style="width: 250px;" />
					</td>
				</tr>
				</table>
				<?php
			}
			?>
			<input type="hidden" name="list_count" value="<?php echo $option_count; ?>" />
		</td>
	</tr>
	<?php } ?>
	<?php if($type == 'select') { ?>
	<tr>
		<td><?php echo $TEXT['SIZE']; ?>:</td>
		<td>
			<?php $form['extra'] = explode(',',$form['extra']); ?>
			<input type="text" name="size" value="<?php echo trim($form['extra'][0]); ?>" style="width: 98%;" maxlength="3" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['ALLOW_MULTIPLE_SELECTIONS']; ?>:</td>
		<td>
			<input type="radio" name="multiselect" id="multiselect_true" value="multiple" <?php if($form['extra'][1] == 'multiple') { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('multiselect_true').checked = true;">
			<?php echo $TEXT['YES']; ?>
			</a>
			&nbsp;
			<input type="radio" name="multiselect" id="multiselect_false" value="" <?php if($form['extra'][1] == '') { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('multiselect_false').checked = true;">
			<?php echo $TEXT['NO']; ?>
			</a>
		</td>
	</tr>
	<?php } elseif($type == 'checkbox' OR $type == 'radio') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['SEPERATOR']; ?>:</td>
		<td>
			<input type="text" name="seperator" value="<?php echo $form['extra']; ?>" style="width: 98%;" />
		</td>
	</tr>
	<?php } ?>
<?php } ?>
<?php if($type != 'heading' AND $type != 'none') { ?>
	<tr>
		<td><?php echo $TEXT['REQUIRED']; ?>:</td>
		<td>
			<input type="radio" name="required" id="required_true" value="1" <?php if($form['required'] == 1) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('required_true').checked = true;">
			<?php echo $TEXT['YES']; ?>
			</a>
			&nbsp;
			<input type="radio" name="required" id="required_false" value="0" <?php if($form['required'] == 0) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('required_false').checked = true;">
			<?php echo $TEXT['NO']; ?>
			</a>
		</td>
	</tr>
<?php } ?>
</table>
<table width="100%">
	<tr>
		<td width="10%"></td>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
			<input class="reset" type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		
		<?php
		// added by John Maats, PCWacht, 12 januar 2006
		if ($type<>'none') {
		?>
			<input class="add" type="button" value="<?php echo $TEXT['ADD'].' '.$TEXT['FIELD']; ?>" onclick="javascript: window.location = '<?php echo LEPTON_URL; ?>/modules/form/add_field.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" />
		<?php } 
		// end addition
		?>
		</td>
	</tr>
</table>
</form>
<?php

// Print admin footer
$admin->print_footer();

?>