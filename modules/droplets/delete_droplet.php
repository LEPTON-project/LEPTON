<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 4.4.9 and higher
 * @version         $Id$
 * @filesource		$HeadURL$
 * @lastmodified    $Date$
 *
 */

require('../../config.php');
require_once( dirname(__FILE__).'/functions.inc.php' );

// Get id
if(!isset($_GET['droplet_id']) OR !is_numeric($_GET['droplet_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$droplet_id = $_GET['droplet_id'];
}

// Include WB admin wrapper script
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');

// fake for wb_handle_export
$_POST['markeddroplet'] = array( $droplet_id );
ob_start();
wb_handle_export();
ob_end_clean();

// check website baker platform (with WB 2.7, Admin-Tools were moved out of settings dialogue)
if(file_exists(ADMIN_PATH .'/admintools/tool.php')) {
	$admintool_link = ADMIN_URL .'/admintools/index.php';
	$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';
	$admin = new admin('admintools', 'admintools');
} else {
	$admintool_link = ADMIN_URL .'/settings/index.php?advanced=yes#administration_tools"';
	$module_edit_link = ADMIN_URL .'/settings/tool.php?tool=droplets';
	$admin = new admin('Settings', 'settings_advanced');
}

// Delete droplet
$database->query("DELETE FROM ".TABLE_PREFIX."mod_droplets WHERE id = '$droplet_id' LIMIT 1");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/droplets/modify_droplet.php?droplet_id='.$droplet_id);
} else {
    $admin->print_success($TEXT['SUCCESS'], $module_edit_link);
}

// Print admin footer
$admin->print_footer();

?>