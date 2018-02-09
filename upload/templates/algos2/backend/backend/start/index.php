<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://lepton-cms.org
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



// get twig instance
$admin = LEPTON_admin::getInstance();
$oTWIG = lib_twig_box::getInstance();

//	Pre-load the theme langages 
LEPTON_basics::getInstance();

/** 
 *	Check if install directory is deleted
 */
if ( (file_exists(LEPTON_PATH.'/install/') ) )  {
	$warning = LEPTON_tools::display('WARNING,<br  />'.$MESSAGE['START_INSTALL_DIR_EXISTS'].'<br />','pre','warning');//("WARNING", "<br  />".$MESSAGE['START_INSTALL_DIR_EXISTS']."<br />");
} else {
	$warning = '';
}


// Insert "Add-ons" section overview (pretty complex compared to normal)
$addons_overview = $TEXT['MANAGE'].' ';
$addons_count = 0;
if($admin->get_permission('modules') == true)
{
	$addons_overview .= '<a href="'.ADMIN_URL.'/modules/index.php">'.$MENU['MODULES'].'</a>';
	$addons_count = 1;
}
if($admin->get_permission('templates') == true)
{
	if($addons_count == 1) { $addons_overview .= ', '; }
	$addons_overview .= '<a href="'.ADMIN_URL.'/templates/index.php">'.$MENU['TEMPLATES'].'</a>';
	$addons_count = 1;
}
if($admin->get_permission('languages') == true)
{
	if($addons_count == 1) { $addons_overview .= ', '; }
	$addons_overview .= '<a href="'.ADMIN_URL.'/languages/index.php">'.$MENU['LANGUAGES'].'</a>';
}

// Insert "Access" section overview (pretty complex compared to normal)
$access_overview = $TEXT['MANAGE'].' ';
$access_count = 0;
if($admin->get_permission('users') == true) {
	$access_overview .= '<a href="'.ADMIN_URL.'/users/index.php">'.$MENU['USERS'].'</a>';
	$access_count = 1;
}
if($admin->get_permission('groups') == true) {
	if($access_count == 1) { $access_overview .= ', '; }
	$access_overview .= '<a href="'.ADMIN_URL.'/groups/index.php">'.$MENU['GROUPS'].'</a>';
	$access_count = 1;
}

$page_values = array(
	'WELCOME_MESSAGE' => $MESSAGE['START_WELCOME_MESSAGE'],
	'CURRENT_USER' => $MESSAGE['START_CURRENT_USER'],
	'DISPLAY_NAME' => $admin->get_display_name(),
	'ADMIN_URL' => ADMIN_URL,
	'LEPTON_URL' => LEPTON_URL,
	'THEME_URL' => THEME_URL,
	'NO_CONTENT' => '<p>&nbsp;</p>',
	'WARNING' => $warning,
	
// only with access
	'PAGES' => $MENU['PAGES'],
	'MEDIA' => $MENU['MEDIA'],
	'ADDONS' => $MENU['ADDONS'],
	'ACCESS' => $MENU['ACCESS'],
	'PREFERENCES' => $MENU['PREFERENCES'],
	'SETTINGS' => $MENU['SETTINGS'],
	'ADMINTOOLS' => $MENU['ADMINTOOLS'],
	'HOME_OVERVIEW' => $OVERVIEW['START'],
	'PAGES_OVERVIEW' => $OVERVIEW['PAGES'],
	'MEDIA_OVERVIEW' => $OVERVIEW['MEDIA'],
	'ADDONS_OVERVIEW' => $addons_overview,
	'ACCESS_OVERVIEW' => $access_overview,
	'PREFERENCES_OVERVIEW' => $OVERVIEW['PREFERENCES'],
	'SETTINGS_OVERVIEW' => $OVERVIEW['SETTINGS'],
	'ADMINTOOLS_OVERVIEW' => $OVERVIEW['ADMINTOOLS']	

);

$oTWIG->registerPath( THEME_PATH."theme","start" );
echo $oTWIG->render(
	"@theme/start.lte",
	$page_values
);

$admin->print_footer();

?>