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

// require_once(LEPTON_PATH.'/framework/class.admin.php');
$admin = new LEPTON_admin('Addons', 'modules_install');

// Include the functions file
require_once(LEPTON_PATH.'/framework/summary.functions.php');

// get marked groups
if ( isset( $_POST['group_id'] ) ) {
    foreach ( $_POST['group_id'] as $gid ) {
        $allowed_groups[] = $gid;
    }
}
else {
// no groups marked, so don't allow any group
    $allowed_groups = array();
}

$new_module_name = trim($_POST['module']);

// get all known groups
$all_groups = array();
$database->execute_query(
    'SELECT * FROM `'.TABLE_PREFIX.'groups` WHERE `group_id` <> 1',
    true,
    $all_groups,
    true
);

foreach($all_groups as $temp_group)
{
    $gid = $temp_group['group_id'];
    
    // Add newly installed module to any group in the $allowed_groups array
    
    if ( in_array( $gid, $allowed_groups ) )
    {
        // Get current value
        $temp_modules = ( $temp_group['module_permissions'] == "" )
            ? array() 
            : explode(',', $temp_group['module_permissions'] )
            ;
        
        // Add newly installed module
        $temp_modules[] = $new_module_name;
        
        // Avoid doubles
        $temp_modules = array_unique( $temp_modules );
        
        // Sort the array
        natsort($temp_modules);
        
        // Update the database
        $fields = array(
            'module_permissions'    => implode(',', $temp_modules)
        );
        
        $database->build_and_execute(
            'update',
            TABLE_PREFIX."groups",
            $fields,
            "`group_id`=".$gid
        );
        
        if($database->is_error()) {
            $admin->print_error($database->get_error());
        }
    }
}

$admin->print_success($MESSAGE['GENERIC_INSTALLED']);

// Print admin footer
$admin->print_footer();

?>