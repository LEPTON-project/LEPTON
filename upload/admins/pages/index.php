<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');
// Include the functions file

// eggsurplus: add child pages for a specific page

// Setup template object
$template = new Template(THEME_PATH.'/templates');
// Insert urls
$template->set_var(array(
    'THEME_URL' => THEME_URL,
    'LEPTON_URL' => LEPTON_URL,
    'LEPTON_PATH' => LEPTON_PATH,
    'ADMIN_URL' => ADMIN_URL,
  )
);

print_search_form( );

print_list_page();


$template->set_file('page', 'pages.htt');
$template->set_block('page', 'main_block', 'main');
// Group list 1
$query = "SELECT * FROM ".TABLE_PREFIX."groups";
$get_groups = $database->query($query);
$template->set_block('main_block', 'group_list_block', 'group_list');
// Insert admin group and current group first
$admin_group_name = $get_groups->fetchRow();
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
while($group = $get_groups->fetchRow()) {
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
$admin_group_name = $get_groups->fetchRow();
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

while($group = $get_groups->fetchRow()) {
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

// Insert language headings
$template->set_var(array(
    'HEADING_ADD_PAGE' => $HEADING['ADD_PAGE']
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
    'TEXT_REGISTERED_VIEWERS' => $TEXT['REGISTERED_VIEWERS']
  )
);

// Insert permissions values
if($admin->get_permission('pages_add') != true) {
  $template->set_var('DISPLAY_ADD', 'hide');
} elseif($admin->get_permission('pages_add_l0') != true && $editable_pages == 0) {
  $template->set_var('DISPLAY_ADD', 'hide');
}

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// include the required file for Javascript admin
if(file_exists(LEPTON_PATH.'/modules/jsadmin/jsadmin_backend_include.php'))
{
  include(LEPTON_PATH.'/modules/jsadmin/jsadmin_backend_include.php');
}

// Print admin
$admin->print_footer();


// Parent page list
function parent_list($parent)
{
  global $admin, $database, $template, $field_set;

  $admin_user_id = $admin->get_user_id();

  $query = "SELECT `page_id`,`admin_groups`,`admin_users`,`menu_title`,`page_title`,`visibility`,`parent`,`level`,`viewing_groups`,`viewing_users` FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' AND visibility!='deleted' ORDER BY position ASC";
  $get_pages = $database->query($query);
  while($page = $get_pages->fetchRow()) {
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

function url_encode($string) {
	$string = html_entity_decode($string,ENT_QUOTES,'UTF-8');
	$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
	$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
	return str_replace($entities, $replacements, rawurlencode($string));
}

function make_list($parent = 0, &$editable_pages = 0) {
  // Get objects and vars from outside this function
  global $admin, $database, $TEXT, $MESSAGE, $HEADING, $par;
  $template = new Template(THEME_PATH.'/templates');
  $template->set_file('pages_list_loop_file', 'pages_list_loop.htt');
  $template->set_block('pages_list_loop_file', 'main_block', 'main');
  $template->set_block('main_block', 'page_list_loop_block', 'page_list_loop');
  $template->set_block('page_list_loop_block', 'page_sublist_loop_block', 'page_sublist_loop');
  $template->set_var('PARENT', $parent);
  $template->set_var('ADMIN_URL', ADMIN_URL);
  $template->set_var('THEME_URL', THEME_URL);
  $template->set_block('page_sublist_loop_block', 'link_view_block', 'link_view');
  $template->set_block('page_sublist_loop_block', 'link_settings_block', 'link_settings');
  $template->set_block('page_sublist_loop_block', 'link_restore_block', 'link_restore');
  $template->set_block('page_sublist_loop_block', 'link_manage_active_block', 'link_manage_active');
  $template->set_block('page_sublist_loop_block', 'link_manage_inactive_block', 'link_manage_inactive');
  $template->set_block('page_sublist_loop_block', 'link_manage_no_date_block', 'link_manage_no_date');
  $template->set_block('page_sublist_loop_block', 'link_move_up_block', 'link_move_up');
  $template->set_block('page_sublist_loop_block', 'link_move_down_block', 'link_move_down');
  $template->set_block('page_sublist_loop_block', 'link_delete_block', 'link_delete');
  $template->set_block('page_sublist_loop_block', 'link_add_block', 'link_add');
  
	if (!isset($_COOKIE["p".$parent])) $_COOKIE["p".$parent] = "1";
	
	if (isset ($_COOKIE['p'.$parent]) && $_COOKIE['p'.$parent] == '1'){
		$template->set_var('DISPLAY', ' style="display:block"');
	} else {
		$template->set_var('DISPLAY', ' style="display:none"');
	}

  // Get page list from database
  $sql = 'SELECT * FROM `'.TABLE_PREFIX.'pages` WHERE `parent` = '.$parent.' ';
  $sql .= (PAGE_TRASH != 'inline') ?  'AND `visibility` != \'deleted\' ' : ' ';
  $sql .= 'ORDER BY `position` ASC';
  $get_pages = $database->query($sql);
  $loop = '';
  // Insert values into main page list
  if($get_pages->numRows() > 0){
    /**
     *	Get the info out of the loop to get rit of fifty+x methods-calls.
     */
    $admin_get_perm = $admin->get_permission('pages_modify');
    $admin_user_id = $admin->get_user_id();

    while($page = $get_pages->fetchRow()){
      $template->set_var('TEXT_EXPAND', $TEXT['EXPAND']);
      $template->set_var('TEXT_COLLAPSE', $TEXT['COLLAPSE']);
      $template->set_var('TEXT_MODIFY', $TEXT['MODIFY']);
      $template->set_var('TEXT_VIEW', $TEXT['VIEW']);
      $template->set_var('TEXT_SETTINGS', $TEXT['SETTINGS']);
      $template->set_var('TEXT_RESTORE', $TEXT['RESTORE']);
      $template->set_var('TEXT_MOVE_UP', $TEXT['MOVE_UP']);
      $template->set_var('TEXT_MOVE_DOWN', $TEXT['MOVE_DOWN']);
      $template->set_var('TEXT_DELETE', $TEXT['DELETE']);
      $template->set_var('HEADING_MANAGE_SECTIONS', $HEADING['MANAGE_SECTIONS']);
      $template->set_var('HEADING_ADD_PAGE', $HEADING['ADD_PAGE']);
      $template->set_var('PAGE_TITLE', $page['page_title']);	// # Aldus 1
      $template->set_var('MENU_TITLE', $page['menu_title']);	// # Aldus 2
      $template->set_var('PAGE_LINK', $page['link'].PAGE_EXTENSION);
      $template->set_var('PAGE_ID', $page['page_id']);
      $template->set_var('PAGE_URL', $admin->page_link($page['link']));

      // Get user perms
      $admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
      $admin_users = explode(',', str_replace('_', '', $page['admin_users']));
      $in_group = FALSE;
      foreach($admin->get_groups_id() as $cur_gid){
        if (in_array($cur_gid, $admin_groups)){
          $in_group = TRUE;
        }
      }
      if(($in_group) || is_numeric(array_search($admin_user_id, $admin_users))){
        if($page['visibility'] == 'deleted'){
          if(PAGE_TRASH == 'inline'){
            $can_modify = true;
            $editable_pages++;
          } else {
            $can_modify = false;
          }
        } elseif($page['visibility'] != 'deleted'){
          $can_modify = true;
          $editable_pages++;
        }
      } else {
        if($page['visibility'] == 'private'){
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
      $template->set_var('LEVEL', $page['level']);
      $template->set_var('EXPAND', '');
		
		if( true === $display_plus ) {
			// print_r($_COOKIE);
			$sign = 'plus';
        	
        	if(!isset($_COOKIE['p'.$page['page_id']])) $_COOKIE['p'.$page['page_id']] = '0';
        	
        	if(isset($_COOKIE['p'.$page['page_id']]) && $_COOKIE['p'.$page['page_id']] == '1'){
        		$sign = 'minus';
        	}
        
        	$theme_url = THEME_URL;
        
        $expand = <<<EXPAND
          <a href="javascript:toggle_visibility('p{$page['page_id']}');" title="{$TEXT['EXPAND']}/{$TEXT['COLLAPSE']}">
            <img src="$theme_url/images/{$sign}_16.png" onclick="toggle_plus_minus('{$page['page_id']}');" name="plus_minus_{$page['page_id']}" alt="+" />
          </a>
EXPAND;
		$template->set_var('EXPAND', $expand);
      }
      // end Aldus: #2
      switch($page['visibility']) {
        case 'public':	$img = "visible_16.png"; $t = $TEXT['PUBLIC'];
          break;

        case 'private':	$img = "private_16.png"; $t = $TEXT['PRIVATE'];
          break;

        case 'registered':	$img = "keys_16.png"; $t = $TEXT['REGISTERED'];
          break;

        case 'hidden':	$img = "hidden_16.png"; $t = $TEXT['HIDDEN'];
          break;

        case 'none':	$img = "none_16.png"; $t = $TEXT['NONE'];
          break;

        case 'deleted':	$img = "deleted_16.png"; $t = $TEXT['DELETED'];
          break;

        default:
          $img = ""; $t = "No matches found in admins/pages/index.php for the visibility!";
      }
      $img_visibility = "<img src='".THEME_URL."/images/".$img."' alt='".$TEXT['VISIBILITY'].":".$t."' class='page_list_rights' />\n";

      $template->set_var('IMG_VISIBILITY', $img_visibility);
      if(true === $admin_can_modify)
        $template->set_var('MODIFY_URL', ADMIN_URL."/pages/modify.php?page_id=".$page['page_id']);

      if($page['visibility'] != 'deleted' && $page['visibility'] != 'none') {
        $template->parse('link_view', 'link_view_block');
      }else{
        $template->parse('link_view', '');
      }

      if($page['visibility'] != 'deleted' && $admin->get_permission('pages_settings') == true && $can_modify == true) {
        $template->parse('link_settings', 'link_settings_block');
        $template->parse('link_restore', '');
      } else {
        $template->parse('link_restore', 'link_restore_block');
        $template->parse('link_settings', '');
      }
	
	// Work-out if we should show the "manage dates" link
	//	Aldus: also handle the "manage-sections"-link!
	if(MANAGE_SECTIONS == 'enabled' && $admin->get_permission('pages_modify')==true && $can_modify==true){
		$sql = 'SELECT `publ_start`, `publ_end` FROM `'.TABLE_PREFIX.'sections` ';
		$sql .= 'WHERE `page_id` = '.$page['page_id'].' AND `module` != \'menu_link\' ';
        $query_sections = $database->query($sql);
        
		if($query_sections->numRows() > 0){

			$mdate_display=false;
			while($mdate_res = $query_sections->fetchRow()){
				if($mdate_res['publ_start']!='0' || $mdate_res['publ_end']!='0'){
					$mdate_display=true;
					break;
				}
			}
			if($mdate_display==1){
				if($admin->page_is_active($page)){
					$template->parse('link_manage_active', 'link_manage_active_block');
				}else{
					$template->parse('link_manage_inactive', 'link_manage_inactive_block');
				}
				$template->parse('link_manage_no_date', '');
			} else {
				$template->parse('link_manage_active', '');
				$template->parse('link_manage_inactive', '');
				$template->parse('link_manage_no_date', 'link_manage_no_date_block');
			}
		} else {
			/**
			 *	There are no sections on the page:
			 */
			$template->parse('link_manage_active', '');
			$template->parse('link_manage_inactive', '');
			$template->parse('link_manage_no_date', 'link_manage_no_date_block');
		}
      }
      
      if($page['position'] != 1 && $page['visibility'] != 'deleted' && $admin->get_permission('pages_settings') == true && $can_modify == true) {
        $template->parse('link_move_up', 'link_move_up_block');
      }else{
        $template->parse('link_move_up', '');
      }
      if($page['position'] != $num_pages && $page['visibility'] != 'deleted' && $admin->get_permission('pages_settings') == true && $can_modify == true) {
        $template->parse('link_move_down', 'link_move_down_block');
      }else{
        $template->parse('link_move_down', '');
      }
      if ($admin->get_permission('pages_delete') == true && $can_modify == true) {
        $okstring = str_replace(array(':', '@', '\''), array('&colon;', '&commat;', "&prime;"), url_encode($page['page_title']));#, ENT_QUOTES));
        $s = sprintf($MESSAGE['PAGES_DELETE_CONFIRM'], $okstring);
        $template->set_var('MESSAGE_PAGES_DELETE_CONFIRM', $s);
        $template->parse('link_delete', 'link_delete_block');
      }else{
        $template->parse('link_delete', '');
      }
      if(($admin->get_permission('pages_add') == true) && ($can_modify == true) && ($page['visibility'] != 'deleted')) {
        $template->parse('link_add', 'link_add_block');
      }else{
        $template->parse('link_add', '');
      }
      if ( $page['parent'] == 0){
        $page_tmp_id = $page['page_id'];
      }
      // Get subs
      $template->set_var('LOOP', make_list($page['page_id'], $editable_pages));
      $template->parse('page_sublist_loop', 'page_sublist_loop_block', true);
    }
    ob_start();
    $template->set_var('PARENT', $parent);
    if (isset ($_COOKIE['p'.$parent]) && $_COOKIE['p'.$parent] == '1'){
      $template->set_var('DISPLAY', ' style="display:block"');
    }
    $template->parse('page_list_loop', 'page_list_loop_block');
    $template->parse('main', 'main_block');
    $template->pparse('output', 'pages_list_loop_file');
    $loop = ob_get_clean();
  }
  $par['num_subs'] = (empty($output) ) ?  1 : $par['num_subs'];
  return $loop;
}

function print_list_page(){
  global $admin, $template, $HEADING, $TEXT;
// Generate pages list
  if($admin->get_permission('pages_view') == true) {
    $template->set_file('pages_list', 'pages_list.htt');
    $template->set_block('pages_list', 'main_block', 'main');
    $template->set_block('main_block', 'page_list_block', 'page_list');
    $template->set_var('HEADING_MODIFY_DELETE_PAGE', $HEADING['MODIFY_DELETE_PAGE']);
    $template->set_var('TEXT_VISIBILITY', $TEXT['VISIBILITY']);
    $template->set_var('TEXT_MENU_TITLE', $TEXT['MENU_TITLE']);
    $template->set_var('TEXT_PAGE_TITLE', $TEXT['PAGE_TITLE']);
    $template->set_var('TEXT_ACTIONS', $TEXT['ACTIONS']);

    $par = array();
    $par['num_subs'] = 1;

    $editable_pages = 0;
    $loop = make_list(0, $editable_pages);
    $template->set_var('PAGES_LIST_LOOP', $loop);
    $template->parse('pages_list', 'page_list_block');
    $template->parse('main', 'main_block');
    $template->pparse('output', 'pages_list');
    if ($editable_pages == 0) echo "</div><div class='empty_list'>".$TEXT['NONE_FOUND']."</div>";
  } else {
    $editable_pages = 0;
    echo "</div><div class='empty_list'>".$TEXT['NONE_FOUND']."</div>";
  }
}

function print_search_form( ) {
  global $TEXT, $HEADING, $template;
  $template->set_file('page_search', 'pages_search.htt');
  $template->set_block('page_search', 'main_block', 'main');
  $template->set_block('main_block', 'search_form_block', 'search_form');
  
  $template->set_block('search_result_block', 'search_empty_block', 'search_empty');
  #$template->clear_var('search_empty');
  
  $template->set_var('HEADING_SEARCH_PAGE', $HEADING['SEARCH_PAGE']);
  $template->set_var('TEXT_SEARCH_FOR', $TEXT['SEARCH_FOR']);
  $template->set_var('TEXT_PAGE_TITLE', $TEXT['PAGE_TITLE']);
  $template->set_var('TEXT_PAGE_ID', $TEXT['PAGE_ID']);
  $template->set_var('TEXT_SECTION_ID', $TEXT['SECTION_ID']);
  $template->set_var('TEXT_SEARCH', $TEXT['SEARCH']);
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
  $template->set_var('SEARCH_FOR_TITLE_CHECKED', $title_checked);
  $template->set_var('SEARCH_FOR_PAGE_CHECKED', $page_checked);
  $template->set_var('SEARCH_FOR_SECTION_CHECKED', $section_checked);
  
  if( isset($_POST['terms']) ) {
    $template->set_var('SEARCH_VALUE', $_POST['terms']);
  }
  
  handle_search();
  
  $template->parse('search_form', 'search_form_block', true);
  $template->parse('main', 'main_block', false);
  $template->pparse('output', 'page_search');
}

// ----- BlackBird Search ID Hack Part II ----
function handle_search () {
  global $TEXT, $database, $admin, $template;
  $template->set_block('search_form_block', 'search_result_block', 'search_result');
  $template->set_block('search_result_block', 'search_result_loop_block', 'search_result_loop');
  $template->set_block('search_result_block', 'edit_search_result_block', 'edit_search_result');
  
  $template->set_block('search_form_block', 'search_empty_block', 'search_empty');

	/**
	 *	Force "terms" to be use only chars and integers
	 *	(Aldus:	2016-09-20)
	 */
	if(isset($_POST['terms'])) $_POST['terms'] = preg_replace("/[^a-zA-Z0-9',]/i", " ", $_POST['terms']);
	 
  $template->set_var('TEXT_PAGE', $TEXT['PAGE']);
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
      while ( $data = $result->fetchRow() ) {
        // Get user perms
        $edit         = true;
        $admin_groups = explode(',', str_replace('_', '', $data['admin_groups']));
        $admin_users  = explode(',', str_replace('_', '', $data['admin_users']) );
        foreach( $admin->get_groups_id() as $cur_gid ) {
          if ( !in_array($cur_gid, $admin_groups) ) {
            $edit = false;
          }
        }
        if($edit){
          $template->set_var('TEXT_PAGE', $TEXT['PAGE']);
          $template->set_var('PAGE_ID', $data['page_id']);
          $template->parse('edit_search_result', 'edit_search_result_block');
        }else{
          $template->parse('edit_search_result', '');
        }
        foreach( array( 'page_id', 'section_id', 'page_title', 'menu_title', 'module', 'block' ) as $field ) {
          if ( isset($data[$field]) ) {
            if(isset($TEXT[strtoupper($field)])){
              $template->set_var('TEXT_FIELD', $TEXT[strtoupper($field)]);
            }else{
              $template->set_var('TEXT_FIELD', ucfirst($field));
            }
            $template->set_var('DATA_FIELD', $data[$field]);
            $template->parse('search_result_loop', 'search_result_loop_block', true);
          }
        }
        $template->parse('search_result', 'search_result_block', true);
        $template->parse('search_result_loop', '');
      }
     }else {
      $template->set_var('TEXT_NONE_FOUND', $TEXT['NONE_FOUND']);
      $template->parse('search_empty', 'search_empty_block', true);
    }
  }
  return true;
}
// ----- BlackBird Search ID Hack Part II ----

?>