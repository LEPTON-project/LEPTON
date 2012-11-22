<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 4.4.9 and higher
 * @version         $Id$
 * @filesource		$HeadURL$
 * @lastmodified    $Date$
 *
 */

require('../../config.php');

// Get id
if(!isset($_GET['droplet_id']) OR !is_numeric($_GET['droplet_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$droplet_id = $_GET['droplet_id'];
}

require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';
$admin = new admin('admintools', 'admintools');

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/droplets/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/droplets/backend.css');
	echo "n</style>n";
}

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/droplets/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php');
	}
}
require_once(WB_PATH . '/include/editarea/wb_wrapper_edit_area.php');
echo registerEditArea ('contentedit','php',true,'both',true,true,600,450,'search, fullscreen, |, undo, redo, |, select_font,|, highlight, reset_highlight, |, help');
		

$modified_when = time();
$modified_by = $admin->get_user_id();

// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_droplets WHERE id = '$droplet_id'");
$fetch_content = $query_content->fetchRow();
$content = (htmlspecialchars($fetch_content['code']));
?>
<div class="container">
<h2 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
	Droplet Edit
</h2>
<div>&nbsp;</div>
<form name="modify" action="<?php echo WB_URL; ?>/modules/droplets/save_droplet.php" method="post" style="margin: 0;">
<input type="hidden" name="data_codepress" value="" />
<input type="hidden" name="droplet_id" value="<?php echo $droplet_id; ?>" />
<input type="hidden" name="show_wysiwyg" value="<?php echo $fetch_content['show_wysiwyg']; ?>" />

<table class="row_a" cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
		<td width="10%" class="setting_name">
			<?php echo $TEXT['NAME']; ?>:
		</td>
		<td width="90%">
			<input type="text" name="title" value="<?php echo stripslashes($fetch_content['name']); ?>" style="width: 38%;" maxlength="32" />
		</td>
	</tr>
	<tr>
		<td valign="top" class="setting_name" width="60px"><?php echo $TEXT['DESCRIPTION']; ?>:</td>
		<td>
			<input type="text" name="description" value="<?php echo stripslashes($fetch_content['description']); ?>" style="width: 98%;" />
		</td>
	</tr>
	<tr>
		<td class="setting_name" width="60px">
			<?php echo $TEXT['ACTIVE']; ?>:
		</td>
		<td>	
			<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;">
			<label><?php echo $TEXT['YES']; ?></label>
			</a>
			<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;">
			<label><?php echo $TEXT['NO']; ?></label>
			</a>
		</td>
	</tr>
<?php
// Next show only if admin is logged in, user_id = 1
if ($modified_by == 1) {
	?>
	<tr>
		<td class="setting_name" width="60px">
			<?php echo $TEXT['ADMIN']; ?>:
		</td>
		<td> 
			<?php echo $DR_TEXT['ADMIN_EDIT']; ?>&nbsp;   	
			<input type="radio" name="admin_edit" id="admin_edit_true" value="1" <?php if($fetch_content['admin_edit'] == 1) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('admin_edit_true').checked = true;">
			<label><?php echo $TEXT['YES']; ?></label>
			</a>
			<input type="radio" name="admin_edit" id="admin_edit_false" value="0" <?php if($fetch_content['admin_edit'] == 0) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('admin_edit_false').checked = true;">
			<label><?php echo $TEXT['NO']; ?></label>
			</a>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
			<?php echo $DR_TEXT['ADMIN_VIEW']; ?>:
			<input type="radio" name="admin_view" id="admin_view_true" value="1" <?php if($fetch_content['admin_view'] == 1) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('admin_view_true').checked = true;">
			<label><?php echo $TEXT['YES']; ?></label>
			</a>
			<input type="radio" name="admin_view" id="admin_view_false" value="0" <?php if($fetch_content['admin_view'] == 0) { echo ' checked="checked"'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('admin_view_false').checked = true;">
			<label><?php echo $TEXT['NO']; ?></label>
			</a>
		</td>
	</tr>
	<?php
}
?>
	<tr>
		<td valign="top" class="setting_name" width="60px"><?php echo $TEXT['CODE']; ?>:</td>
		<td ><textarea name="savecontent" id ="contentedit" style="width: 98%; height: 450px;" rows="50" cols="120"><?php echo $content; ?></textarea>&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2">					
		</td>
	</tr>
	<tr>
		<td valign="top" class="setting_name" width="60px"><?php echo $TEXT['COMMENTS']; ?>:</td>
		<td>
			<textarea name="comments" style="width: 98%; height: 100px;" rows="50" cols="120"><?php echo stripslashes($fetch_content['comments']); ?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;					
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr><td align="right" style="width: 90px;"></td>
		<td align="left">
<?php
// Show only save button if allowed....
if ($modified_by == 1 OR $fetch_content['admin_edit'] == 0 ) {
	?>
			<button class="button submit" name="save" type="submit"><?php echo $TEXT['SAVE']; ?></button>
	<?php
}
?>
<button class="reset" type="button" onclick="javascript: window.location = '<?php echo $module_edit_link; ?>';"><?php echo $TEXT['CANCEL']; ?></button>

		</td>
		
	</tr>
</table>
</form>
<div>&nbsp;</div>

<?php

// Print admin footer
$admin->print_footer();

?>