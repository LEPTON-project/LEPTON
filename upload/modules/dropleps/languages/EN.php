<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
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

$MOD_DROPLEP = array(
 	'Actions' => 'Actions',
 	'Active' => 'Active',
	'Add dropleps' => 'Add Dropleps',    
	'An error occurred when trying to import the Droplep(s)' => 'An error occurred when trying to import the Droplep(s)',  
 	'Backup file deleted: {{file}}' => 'Backup file deleted: {{file}}',
	'Backup created' => 'Backup created', 
 	'Back to overview' => 'Back to overview',   
 	'Contained files' => 'Contained files',
 	'Create new' => 'Create new',  
 	'Date' => 'Date',
	'Delete' => 'Delete',
	'Delete dropleps' => 'Delete Dropleps',  
 	'Description' => 'Description',
 	'Droplep is NOT registered in Search' => 'Droplep is NOT registered in Search',
 	'Droplep is registered in Search' => 'Droplep is registered in Search',    
	'Droplep permissions' => 'Droplep permissions',  
	'Duplicate' => 'Duplicate',
	'Edit datafile' => 'Edit datafile',
	'edit droplep'	=> 'Edit Droplep ',  
	'Edit groups' => 'Edit Groups',    
	'Export' => 'Export',
	'Export dropleps' => 'Export Dropleps',        
 	'Files' => 'Files',
	'Groups' => 'Groups',  
	'Import' => 'Import',
	'Import dropleps' => 'Import Dropleps',  
	'Invalid' => 'Invalid',    
 	'List Backups' => 'List Backups',
 	'Manage backups' => 'Manage backups',
	'Manage permissions' => 'Manage permissions',
	'Manage global permissions' => 'Manage global permissions',
	'Manage Droplep permissions' => 'Manage Droplep permissions',
	'Manage backups' => 'Manage backups',
	'Manage perms' => 'Manage permissions',      
	'marked' => 'marked',
	'Modify' => 'Modify',
	'Modify dropleps' => 'Modify Dropleps',
 	'Name' => 'Name',          
 	'No Backups found' => 'No Backups found',
 	'No Dropleps found' => 'No Dropleps found',
 	'No valid Droplep file (missing description and/or usage instructions)' => 'No valid Droplep file (missing description and/or usage instructions)',  
	'Packaging error' => 'Packaging error',
	'Permissions' => 'Permissions',
	'Permissions saved' => 'Permissions saved',   
	'Please check the syntax!' => 'Please check the syntax!',
	'Please choose a file' => 'Please choose a file',    
	'Please enter a name!' => 'Please enter a name!',
	'Please mark some Dropleps to delete' => 'Please mark some Dropleps to delete',
	'Please mark some Dropleps to export' => 'Please mark some Dropleps to export',
	'Save and Back' => 'Save and Back',   
 	'Search' => 'Search',         
 	'Size' => 'Size',
	'Successfully imported [{{count}}] Droplep(s)' => 'Successfully imported [{{count}}] Droplep(s',  
 	'The Droplep was saved' => 'The Droplep was saved',
	'Unable to delete droplep: {{id}}' => 'Unable to delete droplep: {{id}}',
	'Use' => 'Use',
	'Valid' => 'Valid',
	'View groups' => 'view groups',        
 	'You have entered no code!' => 'You have entered no code!',
 	'You dont have the permission to do this' => 'You dont have the permission to do this'  

);

?>