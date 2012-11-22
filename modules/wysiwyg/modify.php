<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          wysiwyg
 * @author          Ryan Djurovich
 * @author          LEPTON Project
 * @copyright       2004-2010 WebsiteBaker Project
 * @copyright       2010-2011 LEPTON Project 
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 * @version         $Id: modify.php 1172 2011-10-04 15:26:26Z frankh $
 *
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

 

/**
 *	Get page content
 *
 */
$query = "SELECT `content` FROM `".TABLE_PREFIX."mod_wysiwyg` WHERE `section_id`= '".$section_id."'";
$get_content = $database->query($query);
$data = $get_content->fetchRow( MYSQL_ASSOC );
$content = htmlspecialchars($data['content']);

if(!isset($wysiwyg_editor_loaded)) {
	$wysiwyg_editor_loaded=true;

	if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
		
		function show_wysiwyg_editor( $name,$id,$content,$width,$height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
		}
		
	} else {
	
		$id_list= array();
		
		$query_wysiwyg = $database->query("SELECT `section_id` FROM `".TABLE_PREFIX."sections` WHERE `page_id`= '".$page_id."' AND `module`= 'wysiwyg' order by position");
		
		if ( $query_wysiwyg->numRows() > 0) {
			while( !false == ($wysiwyg_section = $query_wysiwyg->fetchRow( MYSQL_ASSOC ) ) ) {
				$temp_id = abs(intval($wysiwyg_section['section_id']));
				$id_list[] = 'content'.$temp_id;
			}

			require_once( WB_PATH."/modules/wysiwyg/classes/pathfinder.php");
			$wb_path_info = new c_pathfinder($database);
			
			require_once(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
			
		}
	}
}

if (isset($preview) && $preview == true) return false;

?>
<div>
<form name="wysiwyg<?php echo $section_id; ?>" action="<?php echo WB_URL; ?>/modules/wysiwyg/save.php" method="post">

<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />

<?php
	show_wysiwyg_editor('content'.$section_id,'content'.$section_id,$content,'100%','250px');
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-bottom: 10px;">
<tr>
	<td align="left">
		<input type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
		<input class="cancel" type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = 'index.php';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

</form>
</div>