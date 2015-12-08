<?php

/**
 *  @module         news
 *  @version        see info.php of this module
 *  @author         Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos), LEPTON Project
 *  @copyright      2004-2010 Ryan Djurovich, Rob Smith, Dietrich Roland Pehlke, Christian M. Stefan (Stefek), Jurgen Nijhuis (Argos) 
 * 	@copyright      2010-2016 LEPTON Project 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

// Include admin wrapper script
require(LEPTON_PATH.'/modules/admin.php');

// Include the ordering class
require(LEPTON_PATH.'/framework/class.order.php');
// Get new order
$order = new order(TABLE_PREFIX.'mod_news_groups', 'position', 'group_id', 'section_id');
$position = $order->get_new($section_id);

// Insert new row into database
$fields = array(
	'section_id'	=> $section_id,
	'page_id'		=> $page_id,
	'position'		=> $position,
	'active'		=> 1
);

$database->build_and_execute(
	"insert",
	TABLE_PREFIX."mod_news_groups",
	$fields
);

// Get the id
$group_id = $database->db_handle->lastInsertId();

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), LEPTON_URL.'/modules/news/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
} else {

?>
<script type="text/javascript">
	setTimeout("top.location.href ='<?php echo LEPTON_URL; ?>/modules/news/modify_group.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&group_id=<?php echo $group_id; ?>'", 0);
</script>
<?php
}

// Print admin footer
$admin->print_footer();

?>