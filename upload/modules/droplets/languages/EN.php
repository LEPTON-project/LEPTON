<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

$MOD_DROPLET = array(
 	'Actions' => 'Actions',
 	'Active' => 'Active',
	'Add droplets' => 'Add droplets',    
	'An error occurred when trying to import the Droplet(s)' => 'An error occurred when trying to import the Droplet(s)',  
 	'Backup file deleted: {{file}}' => 'Backup file deleted: {{file}}',
	'Backup created' => 'Backup created', 
 	'Back to overview' => 'Back to overview',   
 	'Contained files' => 'Contained files',
 	'Create new' => 'Create new',  
 	'Date' => 'Date',
	'Delete' => 'Delete',
	'Delete droplets' => 'Delete droplets',  
 	'Description' => 'Description',
 	'Droplet is NOT registered in Search' => 'Droplet is NOT registered in Search',
 	'Droplet is registered in Search' => 'Droplet is registered in Search',    
	'Droplet permissions' => 'Droplet permissions',  
	'Duplicate' => 'Duplicate',
	'Edit datafile' => 'Edit datafile',
	'edit droplet'	=> 'Edit Droplet ',  
	'Edit groups' => 'Edit Groups',    
	'Export' => 'Export',
	'Export droplets' => 'Export droplets',        
 	'Files' => 'Files',
	'Groups' => 'Groups',  
	'Import' => 'Import',
	'Import droplets' => 'Import droplets',  
	'Invalid' => 'Invalid',    
 	'List Backups' => 'List Backups',
 	'Manage backups' => 'Manage backups',
	'Manage permissions' => 'Manage permissions',
	'Manage global permissions' => 'Manage global permissions',
	'Manage Droplet permissions' => 'Manage Droplet permissions',
	'Manage backups' => 'Manage backups',
	'Manage perms' => 'Manage permissions',      
	'marked' => 'marked',
	'Modify' => 'Modify',
	'Modify droplets' => 'Modify droplets',
 	'Name' => 'Name',          
 	'No Backups found' => 'No Backups found',
 	'No droplets found' => 'No droplets found',
 	'No valid Droplet file (missing description and/or usage instructions)' => 'No valid Droplet file (missing description and/or usage instructions)',  
	'Packaging error' => 'Packaging error',
	'Permissions' => 'Permissions',
	'Permissions saved' => 'Permissions saved',   
	'Please check the syntax!' => 'Please check the syntax!',
	'Please choose a file' => 'Please choose a file',    
	'Please enter a name!' => 'Please enter a name!',
	'Please mark some droplets to delete' => 'Please mark some droplets to delete',
	'Please mark some droplets to export' => 'Please mark some droplets to export',
	'Save and Back' => 'Save and Back',   
 	'Search' => 'Search',         
 	'Size' => 'Size',
	'Successfully imported [{{count}}] Droplet(s)' => 'Successfully imported [{{count}}] Droplet(s)',  
 	'The Droplet was saved' => 'The Droplet was saved',
	'Unable to delete droplet: {{id}}' => 'Unable to delete droplet: {{id}}',
	'Upload failed'	=> 'Upload failed!',
	'Use' => 'Use',
	'Valid' => 'Valid',
	'View groups' => 'view groups',        
 	'You have entered no code!' => 'You have entered no code!',
 	'You dont have the permission to do this' => 'You dont have the permission to do this'  

);

?>