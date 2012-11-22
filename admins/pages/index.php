<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010, Website Baker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');
// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');
// eggsurplus: add child pages for a specific page
?>
<script type="text/javascript" src="<?php print ADMIN_URL; ?>/pages/eggsurplus.js"></script>
<script type="text/javascript" src="<?php print ADMIN_URL; ?>/pages/page_tree.js"></script>
<?php
/*
urlencode function and rawurlencode are mostly based on RFC 1738.
However, since 2005 the current RFC in use for URIs standard is RFC 3986.
Here is a function to encode URLs according to RFC 3986.
*/
function url_encode($string) {
    $string = html_entity_decode($string,ENT_QUOTES,'UTF-8');
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, rawurlencode($string));
}

function set_node ($parent,& $par)
{
    $retval = '';

	if($par['num_subs'] )
	{
    $retval .= "\n".'<ul id="p'.$parent.'"';
	if ($parent != 0)
	{
		$retval .= ' class="page_list"';
		if (isset ($_COOKIE['p'.$parent]) && $_COOKIE['p'.$parent] == '1')
		{
			 $retval .= ' style="display:block"';
		}
	}
	$retval .= ">\n";
 	}

	return $retval;
}
?>
<div class="container">
  <h2>
    <?php echo $HEADING['SEARCH_PAGE']; ?>
   </h2>
<?php
  print_search_form( 'search_page_form', '' );
 ?>
</div>
<?php
function make_list($parent = 0, $editable_pages = 0) {
	// Get objects and vars from outside this function
	global $admin, $template, $database, $TEXT, $MESSAGE, $HEADING, $par;

    print set_node ($parent,$par);

	// Get page list from database
    $sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$parent.' ';
    $sql .= (PAGE_TRASH != 'inline') ?  'AND `visibility` != \'deleted\' ' : ' ';
    $sql .= 'ORDER BY `position` ASC';
	$get_pages = $database->query($sql);

	// Insert values into main page list
	if($get_pages->numRows() > 0)
	{
		/**
		 *	Get the info out of the loop to get rit of fifty+x methods-calls.
		 */
		$admin_get_perm = $admin->get_permission('pages_modify');
		$admin_user_id = $admin->get_user_id();
		
		while($page = $get_pages->fetchRow( MYSQL_ASSOC ))
		{
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			$in_group = FALSE;
			foreach($admin->get_groups_id() as $cur_gid)
            {
				if (in_array($cur_gid, $admin_groups))
                {
					$in_group = TRUE;
				}
			}
			if(($in_group) || is_numeric(array_search($admin_user_id, $admin_users)))
            {
				if($page['visibility'] == 'deleted')
                {
					if(PAGE_TRASH == 'inline')
                    {
						$can_modify = true;
						$editable_pages++;
					} else {
						$can_modify = false;
					}
				} elseif($page['visibility'] != 'deleted')
                {
					$can_modify = true;
					$editable_pages++;
				}
			} else {
				if($page['visibility'] == 'private')
                {
					continue;
				}
				else {
					$can_modify = false;
				}
			}
			
			$admin_can_modify = $admin_get_perm && $can_modify;
			
			// Work out if we should show a plus or not
            $sql = 'SELECT `page_id`,`admin_groups`,`admin_users` FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$page['page_id'].' ';
            if (PAGE_TRASH != 'inline') $sql .= 'AND `visibility` != \'deleted\' ';
           
        	$get_page_subs = $database->query($sql);
			$num_subs = $get_page_subs->numRows();
			$par['num_subs'] = $num_subs;				// why this?

			// Work out how many pages there are for this parent
			$num_pages = $get_pages->numRows();

			$display_plus = ($num_subs > 0) ? true : false;
	
			?>
			<li class="p<?php echo $page['parent']; ?>">
			<table summary="<?php echo $TEXT['EXPAND'].'/'.$TEXT['COLLAPSE']; ?>" class="pages_view" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="middle" width="20" style="padding-left: <?php if($page['level'] > 0){ echo $page['level']*20; } else { echo '7'; } ?>px;">
					<?php
					if( true === $display_plus ) {
					?>
					<a href="javascript:toggle_visibility('p<?php echo $page['page_id']; ?>');" title="<?php echo $TEXT['EXPAND'].'/'.$TEXT['COLLAPSE']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/<?php if(isset($_COOKIE['p'.$page['page_id']]) && $_COOKIE['p'.$page['page_id']] == '1'){ echo 'minus'; } else { echo 'plus'; } ?>_16.png" onclick="toggle_plus_minus('<?php echo $page['page_id']; ?>');" name="plus_minus_<?php echo $page['page_id']; ?>" alt="+" />
					</a>
					<?php
					}
					?>
				</td>
				<td class="list_menu_title">
				<?php
					if( true === $admin_can_modify )
						echo "<a href='".ADMIN_URL."/pages/modify.php?page_id=".$page['page_id']."' title='".$TEXT['MODIFY']."'>\n";
					
					switch($page['visibility']) {
						case 'public':	$img = "visible_16.png"; $t = $TEXT['PUBLIC'];
							break;

						case 'private':	$img = "private_16.png"; $t = $TEXT['PRIVATE']; 
							break;

						case 'registered':	$img = "keys_16.png"; $t = $TEXT['REGISTERED'];
							break;

						case 'hidden':	$img = "hidden_16.png"; $t = $TEXT['HIDDEN'];
							break;

						case 'none':	$img = "none_16.png"; $t = $TEXT['none'];
							break;

						case 'deleted':	$img = "deleted_16.png"; $t = $TEXT['DELETED']; 
							break;
						
						default:
							$img = ""; $t = "No matches found in admins/pages/index.php for the visibility!";
					}

					echo "<img src='".THEME_URL."/images/".$img."' alt='".$TEXT['VISIBILITY'].":".$t."' class='page_list_rights' />\n";
					echo (true === $admin_can_modify) ? '<span class="modify_link">'.$page['menu_title'].'</span></a>' : $page['menu_title'];
				?>
				</td>
				<td class="list_page_title">
					<?php echo ($page['page_title']); ?>
				</td>
				<td class="list_page_URL">
					<?php echo $page['link'].PAGE_EXTENSION; ?>
				</td>        
				<td class="list_page_id">
					<?php echo $page['page_id']; ?>
				</td>
				<td class="list_actions">
					<?php if($page['visibility'] != 'deleted' && $page['visibility'] != 'none') { ?>
					<a href="<?php echo $admin->page_link($page['link']); ?>" target="_blank" title="<?php echo $TEXT['VIEW']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/view_16.png" alt="<?php echo $TEXT['VIEW']; ?>" />
					</a>
					<?php } ?>
				</td>
				<td class="list_actions">
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true && $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/settings.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['SETTINGS']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/settings_16.png" alt="<?php echo $TEXT['SETTINGS']; ?>" />
						</a>
						<?php } ?>
					<?php } else { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/restore.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['RESTORE']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/restore_16.png" alt="<?php echo $TEXT['RESTORE']; ?>" />
						</a>
					<?php } ?>
				</td>
				<!-- MANAGE SECTIONS AND DATES BUTTONS -->
				<td class="list_actions">
				<?php
				// Work-out if we should show the "manage dates" link
				if(MANAGE_SECTIONS == 'enabled' && $admin->get_permission('pages_modify')==true && $can_modify==true)
                {

                    $sql = 'SELECT `publ_start`, `publ_end` FROM `'.TABLE_PREFIX.'sections` ';
                    $sql .= 'WHERE `page_id` = '.$page['page_id'].' AND `module` != \'menu_link\' ';
                    $query_sections = $database->query($sql);

					if($query_sections->numRows() > 0)
                    {
						$mdate_display=false;
						while($mdate_res = $query_sections->fetchRow( MYSQL_ASSOC ))
                        {
							if($mdate_res['publ_start']!='0' || $mdate_res['publ_end']!='0')
                            {
								$mdate_display=true;
								break;
							}
						}
						if($mdate_display==1)
                        {
							$file=$admin->page_is_active($page) ? "clock_16.png" : "clock_red_16.png";
							?>
							<a href="<?php echo ADMIN_URL; ?>/pages/sections.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $HEADING['MANAGE_SECTIONS']; ?>">
							<img src="<?php echo THEME_URL."/images/$file"; ?>" alt="<?php echo $HEADING['MANAGE_SECTIONS']; ?>" />
							</a>
						<?php } else { ?>
							<a href="<?php echo ADMIN_URL; ?>/pages/sections.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $HEADING['MANAGE_SECTIONS']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/noclock_16.png" alt="<?php echo $HEADING['MANAGE_SECTIONS']; ?>" /></a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td class="list_actions">
				<?php if($page['position'] != 1) { ?>
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true && $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/move_up.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/up_16.png" alt="<?php echo $TEXT['MOVE_UP']; ?>" />
						</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td class="list_actions">
				<?php if($page['position'] != $num_pages) { ?>
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true && $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/move_down.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
							<img src="<?php echo THEME_URL; ?>/images/down_16.png" alt="<?php echo $TEXT['MOVE_DOWN']; ?>" />
						</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td class="list_actions">
					<?php
					if ($admin->get_permission('pages_delete') == true && $can_modify == true) {
						$okstring = str_replace(array(':', '@', '\''), array('&colon;', '&commat;', "&prime;"), url_encode($page['page_title']));#, ENT_QUOTES));
						//$s = sprintf($MESSAGE['PAGES_DELETE_CONFIRM'], addslashes( url_encode( $page['page_title'] ) ) );
						$s = sprintf($MESSAGE['PAGES_DELETE_CONFIRM'], $okstring);
						echo "<a href=\"javascript:confirm_delete_page('$s?','".ADMIN_URL."/pages/delete.php?page_id=".$page['page_id']."');\" title=\"".$TEXT['DELETE']."\">";
					?>
						<img src="<?php echo THEME_URL; ?>/images/delete_16.png" alt="<?php echo $TEXT['DELETE']; ?>" />
					</a>
					<?php } ?>
				</td>
				<?php
				// eggsurplus: Add action to add a page as a child
				?>
				<td class="list_actions">
					<?php if(($admin->get_permission('pages_add') == true) && ($can_modify == true) && ($page['visibility'] != 'deleted')) { ?>
					<a href="javascript:add_child_page('<?php echo $page['page_id']; ?>');" title="<?php echo $HEADING['ADD_PAGE']; ?>">
						<img src="<?php echo THEME_URL; ?>/images/siteadd_16.png" name="addpage_<?php echo $page['page_id']; ?>" alt="Add Child Page" />
					</a>
					<?php } ?>
				</td>
				<?php
				// end [IC] jeggers 2009/10/14: Add action to add a page as a child
				?>

			</tr>
			</table>
			<?php
			if ( $page['parent'] == 0)
            {
				$page_tmp_id = $page['page_id'];
			}
			// Get subs
			$editable_pages = make_list($page['page_id'], $editable_pages);
            print '</li>'."\n";
		}
	}
	$output = ($par['num_subs'] )? '</ul>'."\n" : '';
    $par['num_subs'] = (empty($output) ) ?  1 : $par['num_subs'];
    print $output;
	return $editable_pages;
}

// Generate pages list
if($admin->get_permission('pages_view') == true) {

	$html  = "\n<script type=\"text/javascript\">\n";
	$html .= "\tvar WB_URL = '".WB_URL."';\n";
	$html .= "\tvar THEME_URL = '".THEME_URL."';\n";
	$html .= "\tvar ADMIN_URL = '".ADMIN_URL."';\n";
	$html .= "</script>\n";

	echo $html;
?>
	<div class="jsadmin hide"></div>
	<div class="container">
	<table summary="<?php echo $HEADING['MODIFY_DELETE_PAGE']; ?>" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<h2><?php echo $HEADING['MODIFY_DELETE_PAGE']; ?></h2>
		</td>
		
	</tr>
	</table>
	<div class="pages_list">
	<table summary="<?php echo $HEADING['MODIFY_DELETE_PAGE']; ?>" cellpadding="0" cellspacing="0">
	<tr class="pages_list_header">
		<td class="header_list_menu_title">
			<?php echo $TEXT['VISIBILITY'] .' / ' .$TEXT['MENU_TITLE']; ?>:
		</td>
		<td class="header_list_page_title">
			<?php echo $TEXT['PAGE_TITLE']; ?>:
		</td>
    <td class="header_list_page_URL">
	    URL:
    </td>
		<td class="header_list_page_id">
			ID:
		</td>
		<td class="header_list_actions">
			<?php echo $TEXT['ACTIONS']; ?>:
		</td>
	</tr>
	</table>
	<?php


  $par = array();
	$par['num_subs'] = 1;
	$editable_pages = make_list(0, 0);

	if ($editable_pages == 0) echo "</div><div class='empty_list'>".$TEXT['NONE_FOUND']."</div>";

} else {
	$editable_pages = 0;

	echo "</div><div class='empty_list'>".$TEXT['NONE_FOUND']."</div>";

}

// Setup template object
$template = new Template(THEME_PATH.'/templates');
$template->set_file('page', 'pages.htt');
$template->set_block('page', 'main_block', 'main');

// ----- BlackBird Search ID Hack Part I ----

?>
</div>
<?php
// ----- BlackBird Search ID Hack End Part I ----

// Insert values into the add page form

// Group list 1

	$query = "SELECT * FROM ".TABLE_PREFIX."groups";
	$get_groups = $database->query($query);
	$template->set_block('main_block', 'group_list_block', 'group_list');
	// Insert admin group and current group first
	$admin_group_name = $get_groups->fetchRow( MYSQL_ASSOC );
	$template->set_var(array(
		'ID' => 1,
		'TOGGLE' => '1',
		'DISABLED' => ' disabled="disabled"',
		'LINK_COLOR' => '000000',
		'CURSOR' => 'default',
		'NAME' => $admin_group_name['name'],
		'CHECKED' => ' checked="checked"'
		)
	);
	$template->parse('group_list', 'group_list_block', true);

	$admin_groups_id = $admin->get_groups_id();
	while($group = $get_groups->fetchRow( MYSQL_ASSOC )) {
		// check if the user is a member of this group
		$flag_disabled = '';
		$flag_checked =  '';
		$flag_cursor =   'pointer';
		$flag_color =    '';
		if (in_array($group["group_id"], $admin_groups_id)) {
			$flag_disabled = ''; //' disabled';
			$flag_checked =  ' checked="checked"';
			$flag_cursor =   'default';
			$flag_color =    '000000';
		}

		// Check if the group is allowed to edit pages
		$system_permissions = explode(',', $group['system_permissions']);
		if(is_numeric(array_search('pages_modify', $system_permissions))) {
			$template->set_var(array(
				'ID' => $group['group_id'],
				'TOGGLE' => $group['group_id'],
				'CHECKED' => $flag_checked,
				'DISABLED' => $flag_disabled,
				'LINK_COLOR' => $flag_color,
				'CURSOR' => $flag_checked,
				'NAME' => $group['name'],
				)
			);
			$template->parse('group_list', 'group_list_block', true);
		}
	}
// Group list 2

	$query = "SELECT * FROM ".TABLE_PREFIX."groups";

	$get_groups = $database->query($query);
	$template->set_block('main_block', 'group_list_block2', 'group_list2');
	// Insert admin group and current group first
	$admin_group_name = $get_groups->fetchRow( MYSQL_ASSOC );
	$template->set_var(array(
		'ID' => 1,
		'TOGGLE' => '1',
		'DISABLED' => ' disabled="disabled"',
		'LINK_COLOR' => '000000',
		'CURSOR' => 'default',
		'NAME' => $admin_group_name['name'],
		'CHECKED' => ' checked="checked"'
		)
	);
	$template->parse('group_list2', 'group_list_block2', true);

	while($group = $get_groups->fetchRow( MYSQL_ASSOC )) {
		// check if the user is a member of this group
		$flag_disabled = '';
		$flag_checked =  '';
		$flag_cursor =   'pointer';
		$flag_color =    '';
		if (in_array($group["group_id"], $admin_groups_id)) {
			$flag_disabled = ''; //' disabled';
			$flag_checked =  ' checked="checked"';
			$flag_cursor =   'default';
			$flag_color =    '000000';
		}

		$template->set_var(array(
			'ID' => $group['group_id'],
			'TOGGLE' => $group['group_id'],
			'CHECKED' => $flag_checked,
			'DISABLED' => $flag_disabled,
			'LINK_COLOR' => $flag_color,
			'CURSOR' => $flag_cursor,
			'NAME' => $group['name'],
			)
		);
		$template->parse('group_list2', 'group_list_block2', true);
	}


// Parent page list
function parent_list($parent)
{
	global $admin, $database, $template, $field_set;
	
	$admin_user_id = $admin->get_user_id();
	
	$query = "SELECT `page_id`,`admin_groups`,`admin_users`,`menu_title`,`page_title`,`visibility`,`parent`,`level` FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' AND visibility!='deleted' ORDER BY position ASC";
	$get_pages = $database->query($query);
	while($page = $get_pages->fetchRow( MYSQL_ASSOC )) {
		if($admin->page_is_visible($page)==false)
			continue;
		// if parent = 0 set flag_icon
		$template->set_var('FLAG_ROOT_ICON',' none ');
		if( $page['parent'] == 0 && $field_set) {
			$template->set_var('FLAG_ROOT_ICON','url('.THEME_URL.'/images/flags/'.strtolower($page['language']).'.png)');
		}
		// Stop users from adding pages with a level of more than the set page level limit
		if($page['level']+1 < PAGE_LEVEL_LIMIT) {
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));

			$in_group = FALSE;
			foreach($admin->get_groups_id() as $cur_gid) {
				if (in_array($cur_gid, $admin_groups)) {
					$in_group = TRUE;
				}
			}
			if(($in_group) || is_numeric(array_search($admin_user_id, $admin_users))) {
				$can_modify = true;
			} else {
				$can_modify = false;
			}
			// Title -'s prefix
			$title_prefix = '';
			for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - '; }
				$template->set_var(array(
					'ID' => $page['page_id'],
					'TITLE' => ($title_prefix.$page['menu_title']),
					'MENU-TITLE' => ($title_prefix.$page['menu_title']),
					'PAGE-TITLE' => ($title_prefix.$page['page_title'])
					)
				);
				if($can_modify == true) {
					$template->set_var('DISABLED', '');
				} else {
					$template->set_var('DISABLED', ' disabled="disabled" class="disabled"');
				}
				$template->parse('page_list2', 'page_list_block2', true);
		}
		parent_list($page['page_id']);
	}
}
$template->set_block('main_block', 'page_list_block2', 'page_list2');
if($admin->get_permission('pages_add_l0') == true) {
	$template->set_var(array(
		'ID' => '0',
		'TITLE' => $TEXT['NONE'],
		'SELECTED' => ' selected="selected"',
		'DISABLED' => ''
		)
	);
	$template->parse('page_list2', 'page_list_block2', true);
}
parent_list(0);

// Explode module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];
// Modules list
$template->set_block('main_block', 'module_list_block', 'module_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'page' order by name");
if($result->numRows() > 0) {
	while ($module = $result->fetchRow()) {
		// Check if user is allowed to use this module
		if(!is_numeric(array_search($module['directory'], $module_permissions))) {
			$template->set_var('VALUE', $module['directory']);
			$template->set_var('NAME', $module['name']);
			if($module['directory'] == 'wysiwyg') {
				$template->set_var('SELECTED', ' selected="selected"');
			} else {
				$template->set_var('SELECTED', '');
			}
			$template->parse('module_list', 'module_list_block', true);
		}
	}
}

// Insert urls
$template->set_var(array(
	'THEME_URL' => THEME_URL,
	'WB_URL' => WB_URL,
	'WB_PATH' => WB_PATH,
	'ADMIN_URL' => ADMIN_URL,
	)
);

// Insert language headings
$template->set_var(array(
	'HEADING_ADD_PAGE' => $HEADING['ADD_PAGE'],
	'HEADING_MODIFY_INTRO_PAGE' => $HEADING['MODIFY_INTRO_PAGE']
	)
);
// Insert language text and messages
$template->set_var(array(
	'TEXT_TITLE' => $TEXT['TITLE'],
	'TEXT_TYPE' => $TEXT['TYPE'],
	'TEXT_PARENT' => $TEXT['PARENT'],
	'TEXT_VISIBILITY' => $TEXT['VISIBILITY'],
	'TEXT_PUBLIC' => $TEXT['PUBLIC'],
	'TEXT_PRIVATE' => $TEXT['PRIVATE'],
	'TEXT_REGISTERED' => $TEXT['REGISTERED'],
	'TEXT_HIDDEN' => $TEXT['HIDDEN'],
	'TEXT_NONE' => $TEXT['NONE'],
	'TEXT_NONE_FOUND' => $TEXT['NONE_FOUND'],
	'TEXT_ADD' => $TEXT['ADD'],
	'TEXT_RESET' => $TEXT['RESET'],
	'TEXT_ADMINISTRATORS' => $TEXT['ADMINISTRATORS'],
	'TEXT_PRIVATE_VIEWERS' => $TEXT['PRIVATE_VIEWERS'],
	'TEXT_REGISTERED_VIEWERS' => $TEXT['REGISTERED_VIEWERS'],
	'INTRO_LINK' => $MESSAGE['PAGES_INTRO_LINK'],
	)
);

// Insert permissions values
if($admin->get_permission('pages_add') != true) {
	$template->set_var('DISPLAY_ADD', 'hide');
} elseif($admin->get_permission('pages_add_l0') != true && $editable_pages == 0) {
	$template->set_var('DISPLAY_ADD', 'hide');
}
if($admin->get_permission('pages_intro') != true || INTRO_PAGE != 'enabled') {
	$template->set_var('DISPLAY_INTRO', 'hide');
}

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// include the required file for Javascript admin
if(file_exists(WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php'))
{
	include(WB_PATH.'/modules/jsadmin/jsadmin_backend_include.php');
}

// Print admin
$admin->print_footer();

function print_search_form( $id, $display = 'inline-block' ) {
    global $TEXT;
    $section_checked = $page_checked = $title_checked = NULL;
    // ----- create search page/section form -----
    if ( isset($_POST['search_scope']) && $_POST['search_scope'] == 'section' ) {
        $section_checked = 'checked="checked"';
    }
    elseif( isset($_POST['search_scope']) && $_POST['search_scope'] == 'page' ) {
        $page_checked    = 'checked="checked"';
    }
    else {
        $title_checked   = 'checked="checked"';
    }
?>
    <div id="<?php echo $id; ?>" style="display: <?php echo $display; ?>;">
      <form action="<?php echo ADMIN_URL; ?>/pages/index.php" method="post">
        <label for="search_scope"><?php echo $TEXT['SEARCH_FOR']; ?>:</label>
          <input type="radio" name="search_scope" value="title" <?php echo $title_checked; ?> /> <?php echo $TEXT['PAGE_TITLE']; ?>
          <input type="radio" name="search_scope" value="page" <?php echo $page_checked; ?> /> <?php echo $TEXT['PAGE_ID']; ?>
          <input type="radio" name="search_scope" value="section" <?php echo $section_checked; ?> /> <?php echo $TEXT['SECTION_ID']; ?> :
          <input type="text" name="terms" value="<?php echo ( isset($_POST['terms']) ) ? $_POST['terms'] : '' ?>" />
          <input type="submit" name="search" class="search" value="<?php echo $TEXT['SEARCH'] ?>" />
      </form>
      <?php handle_search(); ?>
    </div>
<?php
}
// ----- BlackBird Search ID Hack Part II ----
function handle_search () {
    global $TEXT, $database, $admin;
    if ( isset($_POST['search']) && isset($_POST['terms']) ) {
        $sql = 'SELECT * FROM '.TABLE_PREFIX.'pages AS p';
        if ( isset($_POST['search_scope']) && $_POST['search_scope'] == 'section' ) {
            $sql .= ' JOIN '.TABLE_PREFIX.'sections AS s ON p.page_id=s.page_id';
        }
        $sql .= ' WHERE ';
        if ( isset($_POST['search_scope']) && $_POST['search_scope'] == 'section' ) {
            $sql .= 's.section_id="'.$_POST['terms'].'"';
        }
        elseif ( isset($_POST['search_scope']) && $_POST['search_scope'] == 'title' ) {
            $sql .= 'p.page_title LIKE "%'.$_POST['terms'].'%" OR p.menu_title LIKE "%'.$_POST['terms'].'%"';
        }
        else {
            $sql .= 'p.page_id="'.$_POST['terms'].'"';
        }
        $sql   .= ( PAGE_TRASH != 'inline' )
                ?  ' AND `visibility` != \'deleted\' '
                :  ' '
                ;
        $result = $database->query($sql);
        $data   = array();
        if ( $result->numRows() > 0 ) {
            echo "<br /><br /><table style=\"border: 1px solid #ccc; width: 80%; border-collapse: collapse;\">";
            while ( $data = $result->fetchRow(MYSQL_ASSOC) ) {
                // Get user perms
                $edit         = false;
                $admin_groups = explode(',', str_replace('_', '', $data['admin_groups']));
                $admin_users  = explode(',', str_replace('_', '', $data['admin_users']) );
                foreach( $admin->get_groups_id() as $cur_gid ) {
                    if ( in_array($cur_gid, $admin_groups) ) {
                        $edit = TRUE;
                    }
                }
                echo "<tr><th colspan=\"2\" style=\"background-color:#e1e1e1;padding:3px;border:1px solid #ccc;\">",
                     $TEXT['PAGE'],
                     (
                         $edit
                       ?   "<span style=\"float: right;\">"
						 . "<a href=\"".ADMIN_URL."/pages/modify.php?page_id=".$data['page_id']."\" title=\"".$TEXT['MODIFY']."\">"
                         . "<img src=\"".THEME_URL."/images/modify_16.png\" alt=\"".$TEXT['MODIFY']."\" />"
                         . "</a>&nbsp;"
                         . "<a href=\"".ADMIN_URL."/pages/settings.php?page_id=".$data['page_id']."\" title=\"".$TEXT['SETTINGS']."\">"
                         . "<img src=\"".THEME_URL."/images/settings_16.png\" alt=\"".$TEXT['SETTINGS']."\" />"
                         . "</a>&nbsp;"
                          . "<a href=\"".ADMIN_URL."/pages/sections.php?page_id=".$data['page_id']."\" title=\"".$TEXT['SECTION']."\">"
                         . "<img src=\"".THEME_URL."/images/noclock_16.png\" alt=\"".$TEXT['SECTION']."\" />"
                         . "</a>"
                         . "</span>"
                       : ""
                     ),
                     "</th></tr>"
                     ;
                foreach( array( 'page_id', 'section_id', 'page_title', 'menu_title', 'module', 'block' ) as $field ) {
                    if ( isset($data[$field]) ) {
                        echo "<tr><td style=\"font-weight: bold;\">",
                             (isset($TEXT[strtoupper($field)]) ? $TEXT[strtoupper($field)] : ucfirst($field) ),
                             "</td><td>", $data[$field], "</td>";
                    }
                }
            }
            echo "</table><br />";
        }
        else {
            echo "<strong>", $TEXT['NONE_FOUND'], "</strong><br />";
        }
    }
    return true;
}
// ----- BlackBird Search ID Hack Part II ----

?>