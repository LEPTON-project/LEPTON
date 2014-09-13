<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos)
 *  @copyright      2004-2013 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
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

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/news/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/news/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/news/languages/'.LANGUAGE .'.php');
}
global $database;

$fetch_content = array();
$database->prepare_and_execute(
	"SELECT * FROM `".TABLE_PREFIX."mod_news_settings` WHERE `section_id` = '".$section_id."'",
	true,
	$fetch_content,
	false
);

?>
<div class="container">
<h2><?php echo $MOD_NEWS['SETTINGS']; ?></h2>
<?php

// include the button to edit the optional module CSS files (function added with WB 2.7)
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css'))
{
	edit_module_css('news');
}
?>

<form name="modify" action="<?php echo WB_URL; ?>/modules/news/save_settings.php" method="post" style="margin: 0;">

	<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
	<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<?php
$leptoken_add = (isset($_GET['leptoken']) ? $_GET['leptoken'] : "");
if ( strlen( $leptoken_add ) > 0 ) {
	echo "\n\t<input type='hidden' name='leptoken' value='".$leptoken_add."' />\n";
} ?>
	<table cellpadding="2" cellspacing="0" width="100%">
		<tr>
			<td colspan="2"><strong><?php echo $HEADING['GENERAL_SETTINGS']; ?></strong></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['POSTS_PER_PAGE']; ?>:</td>
			<td class="setting_value">
				<select name="posts_per_page" style="width: 98%;">
					<option value=""><?php echo $TEXT['UNLIMITED']; ?></option>
					<?php
					for($i = 1; $i <= 20; $i++) {
						if($fetch_content['posts_per_page'] == ($i*5)) { $selected = ' selected="selected"'; } else { $selected = ''; }
						echo '<option value="'.($i*5).'"'.$selected.'>'.($i*5).'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<?php if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */ ?>
		<tr>
			<td class="setting_name"><?php echo $TEXT['RESIZE_IMAGE_TO']; ?>:</td>
			<td class="setting_value">
				<select name="resize" style="width: 98%;">
					<option value=""><?php echo $TEXT['NONE']; ?></option>
					<?php
					$SIZES['50'] = 'Max. 50px';
					$SIZES['75'] = 'Max. 75px';
					$SIZES['100'] = 'Max. 100px';
					$SIZES['125'] = 'Max. 125px';
					$SIZES['150'] = 'Max. 150px';
					foreach($SIZES AS $size => $size_name) {
						if($fetch_content['resize'] == $size) { $selected = ' selected="selected"'; } else { $selected = ''; }
						echo '<option value="'.$size.'"'.$selected.'>'.$size_name.'</option>';
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>    
	</table>
	<div style="margin-bottom: 10px;">&nbsp;</div>
	<table  cellpadding="2" cellspacing="0" width="100%" style="margin-top: 3px;">
		<tr>
			<td colspan="2"><strong><?php echo $TEXT['COMMENTS']; ?></strong></td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['COMMENTING']; ?>:</td>
			<td class="setting_value">
				<select name="commenting" style="width: 98%;">
					<option value="none"><?php echo $TEXT['DISABLED']; ?></option>
					<option value="public" <?php if($fetch_content['commenting'] == 'public') { echo ' selected="selected"'; } ?>><?php echo $TEXT['PUBLIC']; ?></option>
					<option value="private" <?php if($fetch_content['commenting'] == 'private') { echo 'selected="selected"'; } ?>><?php echo $TEXT['PRIVATE']; ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="setting_name"><?php echo $TEXT['CAPTCHA_VERIFICATION']; ?>:</td>
			<td>
				<input type="radio" name="use_captcha" id="use_captcha_true" value="1"<?php if($fetch_content['use_captcha'] == true) { echo ' checked="checked"'; } ?> />
				<label for="use_captcha_true"><?php echo $TEXT['ENABLED']; ?></label>
				<input type="radio" name="use_captcha" id="use_captcha_false" value="0"<?php if($fetch_content['use_captcha'] == false) { echo ' checked="checked"'; } ?> />
				<label for="use_captcha_false"><?php echo $TEXT['DISABLED']; ?></label>
			</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="left">
				<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
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