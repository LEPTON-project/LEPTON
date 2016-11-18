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
$admin = new admin('Pages', 'pages_add');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

global $MESSAGE;
global $database;

// Get values
$title = $admin->get_post('title');
if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
    $title = htmlspecialchars($title, ENT_COMPAT | ENT_HTML401 , DEFAULT_CHARSET);
}
else {
    $title = htmlspecialchars($title, ENT_COMPAT, DEFAULT_CHARSET);
}
$module = $admin->get_post('type');
$parent = intval($admin->get_post('parent'));	// force $parent to be an integer
$visibility = $admin->get_post('visibility');
$admin_groups = $admin->get_post('admin_groups');
$viewing_groups = $admin->get_post('viewing_groups');

// add Admin and view groups
$admin_groups[] = 1;
$viewing_groups[] = 1;

if ($parent!=0) {
	if (!$admin->get_page_permission($parent,'admin'))
    {
        $admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
    }

} elseif (!$admin->get_permission('pages_add_l0','system'))
{
	$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
}	

// Validate data
if($title == '' || substr($title,0,1)=='.')
{
	$admin->print_error($MESSAGE['PAGES_BLANK_PAGE_TITLE']);
}

// Check to see if page created has needed permissions
if(!in_array(1, $admin->get_groups_id()))
{
	$admin_perm_ok = false;
	foreach ($admin_groups as $adm_group)
    {
		if (in_array($adm_group, $admin->get_groups_id()))
        {
			$admin_perm_ok = true;
		}
	}
	if ($admin_perm_ok == false)
    {
		$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
	}
	$admin_perm_ok = false;
	foreach ($viewing_groups as $view_group)
    {
		if (in_array($view_group, $admin->get_groups_id()))
        {
			$admin_perm_ok = true;
		}
	}
	if ($admin_perm_ok == false)
    {
		$admin->print_error($MESSAGE['PAGES_INSUFFICIENT_PERMISSIONS']);
	}
}

$admin_groups = implode(',', $admin_groups);
$viewing_groups = implode(',', $viewing_groups);

// Work-out what the link and page filename should be
if($parent == '0')
{
	$link = '/'.page_filename($title);
	// rename menu title: index to prevent clashes with core file /pages/index.php
	if($link == '/index')
    {
		$link .= '_0';
		$filename = LEPTON_PATH .PAGES_DIRECTORY .'/' .page_filename($title) .'_0' .PAGE_EXTENSION;
	} else {
		$filename = LEPTON_PATH.PAGES_DIRECTORY.'/'.page_filename($title).PAGE_EXTENSION;
	}
} else {
	$parent_section = '';
	$parent_titles = array_reverse(get_parent_titles($parent));
	foreach($parent_titles AS $parent_title)
    {
		$parent_section .= page_filename($parent_title).'/';
	}
	if($parent_section == '/') { $parent_section = ''; }
	$link = '/'.$parent_section.page_filename($title);
	$filename = LEPTON_PATH.PAGES_DIRECTORY.'/'.$parent_section.page_filename($title).PAGE_EXTENSION;
	make_dir(LEPTON_PATH.PAGES_DIRECTORY.'/'.$parent_section);
	
	/**
	 *	Copy the "template" of the default index.php to the new location
	 */
	$source = ADMIN_PATH."/pages/master_index.php";
	copy($source, LEPTON_PATH.PAGES_DIRECTORY.'/'.$parent_section."/index.php");
}

// Check if a page with same page filename exists
$get_same_page = $database->query("SELECT page_id FROM ".TABLE_PREFIX."pages WHERE link = '$link'");
if($get_same_page->numRows() > 0 OR file_exists(LEPTON_PATH.PAGES_DIRECTORY.$link.PAGE_EXTENSION) OR file_exists(LEPTON_PATH.PAGES_DIRECTORY.$link.'/'))
{
	$admin->print_error($MESSAGE['PAGES_PAGE_EXISTS']);
}

// Include the ordering class
require(LEPTON_PATH.'/framework/class.order.php');
$order = new order(TABLE_PREFIX.'pages', 'position', 'page_id', 'parent');
// First clean order
$order->clean($parent);
// Get new order
$position = $order->get_new($parent);

// Work-out if the page parent (if selected) has a seperate template or language to the default
$query_parent = $database->query("SELECT template, language FROM ".TABLE_PREFIX."pages WHERE page_id = '$parent'");
if($query_parent->numRows() > 0)
{
	$fetch_parent = $query_parent->fetchRow();
	$template = $fetch_parent['template'];
	$language = $fetch_parent['language'];
} else {
	$template = '';
	$language = DEFAULT_LANGUAGE;
}

$fields = array(
	'parent' 		=> $parent,
	'target'		=> "_top",
	'page_title'	=> $title,
	'menu_title'	=> $title,
	'template'		=> $template,
	'visibility'	=> $visibility,
	'position'		=> $position,
	'menu'			=> 1,
	'language'		=> $language,
	'searching'		=> 1,
	'modified_when'	=> time(),
	'modified_by'	=> $admin->get_user_id(),
	'admin_groups'	=> $admin_groups,
	'viewing_groups'	=> $viewing_groups,
	'link'			=> '',	// ?
	'description'	=> '',
	'keywords'		=> '',
	'page_trail'	=> '',
	'admin_users'	=> '',
	'viewing_users'	=> ''
);

$database->build_and_execute(
	'insert',
	TABLE_PREFIX.'pages',
	$fields
);

if($database->is_error())
{
	$admin->print_error($database->get_error());
}

// Get the page id
$page_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Work out level
$level = level_count($page_id);
// Work out root parent
$root_parent = root_parent($page_id);
// Work out page trail
$page_trail = get_page_trail($page_id);

// Update page with new level and link
$fields = array(
	'root_parent'	=> $root_parent,
	'level'			=> $level,
	'link'			=> $link,
	'page_trail'	=> $page_trail
);

$database->build_and_execute(
	'update',
	TABLE_PREFIX.'pages',
	$fields,
	'page_id = '.$page_id
);

if($database->is_error())
{
	$admin->print_error($database->get_error());
}

// Create a new file in the /pages dir
// m.f.i [1] Aldus 2016-07-19: Details for "wrong" path inside this function?
create_access_file($filename, $page_id, $level);

// add position 1 to new page
$position = 1;

// Add new record into the sections table
$fields = array(
	'page_id'	=> $page_id,
	'position'	=> $position,
	'module'	=> $module,
	'block'		=> 1
);

$database->build_and_execute(
	'insert',
	TABLE_PREFIX.'sections',
	$fields,
	'page_id = '.$page_id
);

if($database->is_error())
{
	$admin->print_error($database->get_error());
}

// Get the section id
$section_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Include the selected modules add file if it exists
if(file_exists(LEPTON_PATH.'/modules/'.$module.'/add.php')) {
	require(LEPTON_PATH.'/modules/'.$module.'/add.php');
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['PAGES_ADDED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>