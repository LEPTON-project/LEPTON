<?php

/**
 *
 *    @module            quickform
 *    @version        see info.php of this module
 *    @authors        Ruud Eisinga, LEPTON project
 *    @copyright        2012-2017 Ruud Eisinga, LEPTON project
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
$print_info_banner = false;
// Include admin wrapper script
require LEPTON_PATH.'/modules/admin.php';

/**
 *    Load Language file
 */
$langfile = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($langfile) ? (dirname(__FILE__))."/languages/EN.php" : $langfile );

require_once LEPTON_PATH.'/modules/edit_area/class.editorinfo.php';


require_once LEPTON_PATH.'/framework/summary.module_edit_css.php';

$backlink = ADMIN_URL.'/pages/modify.php?page_id='.(int)$page_id;

$_action = (isset($_POST['action']) ? strtolower($_POST['action']) : '');
if ($_action != 'save') $_action = 'edit';

/** **********************
 *  Get the page-language
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
    
    $template = addslashes ($_GET['name']);    
    $filename = dirname(__FILE__).'/templates/'.$page_language.'/'.$template;    // !
    $data = '';
    if (file_exists($filename)) $data = file_get_contents($filename) ;
    
	$edit_area = edit_area::registerEditArea('code_area', 'html');
    
    $oTwig = lib_twig_box::getInstance();
    $oTwig->registerModule( "quickform" );
    
    echo $oTwig->render(
        "@quickform/modify_template.lte",
        array(
            'form_action'   => $_SERVER['SCRIPT_NAME'],
            'edit_area'     => $edit_area,
            'MOD_QUICKFORM' => $MOD_QUICKFORM,
            'template'      => $template,
            'data'          => htmlspecialchars($data),
            'leptoken'      => get_leptoken(),
            'page_id'       => $page_id,
            'section_id'    => $section_id
        )
    ); 
}

// Print admin footer
$admin->print_footer();
