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
 * @copyright       2010-2018 LEPTON Project
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

/**
 * check if there is anything to do
 */

if (!(isset($_POST['action']) && in_array($_POST['action'], array('install', 'upgrade', 'uninstall')))) { die(header('Location: index.php?advanced')); }
if (!(isset($_POST['file']) && $_POST['file'] != '' && (strpos($_POST['file'], '..') === false))){  die(header('Location: index.php?advanced'));  }

/**
 * check if user has permissions to access this file
 */
// check user permissions for admintools (redirect users with wrong permissions)
$admin = new LEPTON_admin('Admintools', 'admintools', false, false);
if ($admin->get_permission('admintools') == false) { 
	die(header('Location: ../../index.php')); 
}

// check if the referer URL if available
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 
	(isset($HTTP_SERVER_VARS['HTTP_REFERER']) ? $HTTP_SERVER_VARS['HTTP_REFERER'] : '');

// if referer is set, check if script was invoked from "admin/modules/index.php"
$required_url = ADMIN_URL . '/modules/index.php';
if ($referer != '' && (!(strpos($referer, $required_url) !== false))) { 
	die(header('Location: ../../index.php')); 
}

// include functions file
require_once(LEPTON_PATH . '/framework/summary.functions.php');


// create Admin object with admin header
$admin = new LEPTON_admin('Addons', '', true, false);
$js_back = ADMIN_URL . '/modules/index.php?advanced';

/**
 * Manually execute the specified module file (install.php, upgrade.php)
 */
// check if specified module folder exists
$mod_path = LEPTON_PATH . '/modules/' . basename(LEPTON_PATH . '/' . $_POST['file']);

// let the old variablename if module use it
$module_dir = $mod_path;
if (!file_exists($mod_path . '/' . $_POST['action'] . '.php'))		{
    $admin->print_error($TEXT['NOT_FOUND'].': <tt>"'.htmlentities(basename($mod_path)).'/'.$_POST['action'].'.php"</tt> ', $js_back);
}

// include modules install.php script
require($mod_path . '/' . $_POST['action'] . '.php');

// load module info into database and output status message
require( $mod_path."/info.php");
//load_module($mod_path, false);

// Finish installation
if ( $_POST['action'] == "install" ) {
	  // Load module info into DB
	  load_module(LEPTON_PATH.'/modules/'.$module_directory, false);
	  // let admin set access permissions for modules of type 'page' and 'tool'
	  if ( $module_function == 'page' || $module_function == 'tool' ) {
    	  // get groups
    	  $stmt = $database->query( 'SELECT * FROM '.TABLE_PREFIX.'groups WHERE group_id <> 1' );
    	  if ( $stmt->numRows() > 0 ) {
            echo "<script type=\"text/javascript\">\n",
                 "function markall() {\n",
                 "  for ( i=0; i<document.forms[0].elements.length; i++ ) {\n",
                 "    if ( document.forms[0].elements[i].type == \"checkbox\" ) {\n",
                 "      if ( document.forms[0].group_all.checked == true ) {\n",
                 "        document.forms[0].elements[i].checked=true;\n",
                 "      } else {\n",
                 "        document.forms[0].elements[i].checked=false;\n",
                 "      }\n",
                 "    }\n",
                 "  }\n",
                 "}\n",
                 "</script>\n",
                 "<h2>", $MESSAGE['GENERIC_INSTALLED'], "</h2><br /><br />\n",
                 "<h3>", $TEXT['MODULE_PERMISSIONS'], "</h3><br />\n",
                 "<form method=\"post\" action=\"".ADMIN_URL."/modules/save_permissions.php\">\n",
                 "<input type=\"hidden\" name=\"module\" id=\"module\" value=\"", $module_directory, "\" />\n",
                 "<input type=\"checkbox\" name=\"group_all\" id=\"group_all\" onclick=\"markall();\" /> ", $MESSAGE['ADDON_GROUPS_MARKALL'], "<br />\n"
                 ;
            // let the admin choose which groups can access this module
            while( $row = $stmt->fetchRow() ) {
                echo "<input type=\"checkbox\" name=\"group_id[]\" id=\"group_id[]\" value=\"", $row['group_id'], "\" /> ", $row['name'], "<br />\n";
            }
            echo "<br /><br /><input type=\"submit\" value=\"".$TEXT['SAVE']."\" /></form>";

 			$admin->print_footer();
			exit();

		}
    }
}

$msg = $TEXT['EXECUTE'] . ': <tt>"' . htmlentities(basename($mod_path)) . '/' . $_POST['action'] . '.php"</tt>';

switch ($_POST['action'])
{
	case 'install':
	case 'upgrade':
		$admin->print_success($msg, $js_back);
		break;
		
	default:
		$admin->print_error( $TEXT["ACTION_NOT_SUPPORTED"], $js_back);
}

?>