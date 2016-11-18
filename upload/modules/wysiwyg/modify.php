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
 * @copyright       2010-2017 LEPTON Project 
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
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

/**
 *	Get page content
 *
 */
$query = "SELECT `content` FROM `".TABLE_PREFIX."mod_wysiwyg` WHERE `section_id`= '".$section_id."'";
$get_content = $database->query($query);
$data = $get_content->fetchRow();
$content = htmlspecialchars($data['content']);

/**
 *	Try to add an \ before the "$" char.
 */
$content = str_replace("\$", "\\\$", $content);

if(!isset($wysiwyg_editor_loaded)) {
	$wysiwyg_editor_loaded=true;

	if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(LEPTON_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
		
		function show_wysiwyg_editor( $name,$id,$content,$width,$height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
		}
		
	} else {
	
		$id_list= array();
		$all_sections = array();
		$database->execute_query(
			"SELECT `section_id` FROM `".TABLE_PREFIX."sections` WHERE `page_id`= '".$page_id."' AND `module`= 'wysiwyg' order by position",
			true,
			$all_sections
		);
		
		if ( count($all_sections) > 0) {
			foreach($all_sections as $wysiwyg_section) {
				$temp_id = abs(intval($wysiwyg_section['section_id']));
				$id_list[] = 'content'.$temp_id;
			}

			require_once(LEPTON_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
			
		}
	}
}

if (isset($preview) && $preview == true) return false;

/**	*******************************
 *	Try to get the template-engine.
 */
global $parser, $loader;
require( dirname(__FILE__)."/register_parser.php" );

$form_values = array(
	'LEPTON_URL'	=> LEPTON_URL,
	'page_id'		=> $page_id,
	'section_id'	=> $section_id,
	'TEXT'			=> $TEXT,
	'wysiwyg_editor' => $twig_util->capture_echo( "show_wysiwyg_editor('content".$section_id."','content".$section_id."',\"".$content."\",'100%','250px');")
);

$twig_util->resolve_path("modify.lte");

echo $parser->render( 
	$twig_modul_namespace."modify.lte", // template-filename
	$form_values	//	template-data
);
	
?>