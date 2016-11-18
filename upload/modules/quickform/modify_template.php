<?php

/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2017 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {
	include(LEPTON_PATH.'/framework/class.secure.php');
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

// $admin_header = true;
// Tells script to update when this page was last updated
// show the info banner
$print_info_banner = true;
// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

/**
 *	Load Language file
 */
$langfile = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($langfile) ? (dirname(__FILE__))."/languages/EN.php" : $langfile );

require_once(LEPTON_PATH.'/modules/edit_area/class.editorinfo.php');

require_once(LEPTON_PATH.'/framework/summary.module_edit_css.php');
$backlink = ADMIN_URL.'/pages/modify.php?page_id='.(int)$page_id;

$_action = (isset($_POST['action']) ? strtolower($_POST['action']) : '');
if ($_action != 'save') $_action = 'edit';

/**	**********************
 *	Get the page-language
 */
$page_language = $database->get_one("SELECT `language` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$page_id);
$page_language = strtolower($page_language);

if ($_action == 'save') {
	$template = strtolower(addslashes($_POST['name']));
	if (get_magic_quotes_gpc()) {
		$data = stripslashes($_POST['template_data']);
	}
	else {
		$data = $_POST['template_data'];
	}
	

	$filename = dirname(__FILE__).'/templates/'.$page_language.'/'.$template;
	if (!false == file_put_contents($filename,$data)) {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else { 
		$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
} else {
	
//	$template = $admin->add_slashes($_GET['name']);
	$template = addslashes ($_GET['name']);	
	$filename = dirname(__FILE__).'/templates/'.$page_language.'/'.$template;	// !
	$data = '';
	if (file_exists($filename)) $data = file_get_contents($filename) ;
	echo (function_exists('registerEditArea')) ? registerEditArea('code_area', 'html') : 'none';
	?>
	<form name="edit_module_file" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post" style="margin: 0;">
			<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
			<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
			<input type="hidden" name="action" value="save" />
			<span><?php echo $MOD_QUICKFORM['SAVEAS']; ?>: </span><input type="text" name="name" value="<?php echo $template; ?>" />
			<span style="float:right"><a href="<?php echo LEPTON_URL;?>/modules/quickform/README.txt" target="blank">Help</a></span>
			<textarea id="code_area" name="template_data" cols="100" rows="25" wrap="VIRTUAL" style="margin:2px;width:100%;"><?php echo htmlspecialchars($data); ?></textarea>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td class="left">
					<input name="save" type="submit" value="<?php echo $TEXT['SAVE'];?>" style="width: 100px; margin-top: 5px;" />
					</td>
					<td class="right">
					<input type="button" value="<?php echo $TEXT['CANCEL']; ?>"
							onclick="javascript: window.location = '<?php echo ADMIN_URL;?>/pages/modify.php?page_id=<?php echo $page_id; ?>';"
							style="width: 100px; margin-top: 5px;" />
					</td>
				</tr>
			</table>
	</form>
	<?php 
}
// Print admin footer
$admin->print_footer();
