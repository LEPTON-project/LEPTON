<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          droplets
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
 	'Actions' => 'Aktionen',
 	'Active' => 'Aktiv',
	'Add dropleps' => 'Dropleps hinzuf&uuml;gen',    
	'An error occurred when trying to import the Droplep(s)' => 'Beim Import ist ein Fehler aufgetreten',  
 	'Backup file deleted: {{file}}' => 'Backup Datei gel&ouml;scht: {{file}}',
	'Backup created' => 'Backup erzeugt', 
 	'Back to overview' => 'Zur&uuml;ck zur &Uuml;bersicht',   
 	'Contained files' => 'Enthaltene Dateien',
 	'Create new' => 'Neues Droplep',  
 	'Date' => 'Erstelldatum',
	'Delete' => 'L&ouml;schen',
	'Delete dropleps' => 'Dropleps l&ouml;schen',  
 	'Description' => 'Beschreibung',
 	'Droplep is NOT registered in Search' => 'Das Droplep ist NICHT f&uuml;r die Suche aktiv',
 	'Droplep is registered in Search' => 'Das Droplep ist f&uuml;r die Suche aktiv',    
	'Droplep permissions' => 'Droplep Rechte',  
	'Duplicate' => 'Kopieren',
	'Edit datafile' => 'Datendatei bearbeiten',
	'Edit droplep'	=> 'Droplep bearbeiten',  
	'Edit groups' => 'Dieses Droplep bearbeiten',    
	'Export' => 'Exportieren',
	'Export dropleps' => 'Dropleps exportieren',        
 	'Files' => 'Dateien',
	'Groups' => 'Gruppen',  
	'Import' => 'Importieren',
	'Import dropleps' => 'Dropleps importieren',  
	'Invalid' => 'Nicht valide',    
 	'List Backups' => 'Backups auflisten',
 	'Manage backups' => 'Backups verwalten',
	'Manage permissions' => 'Rechte verwalten',
	'Manage global permissions' => 'Globale Rechte verwalten',
	'Manage Droplep permissions' => 'Droplep Rechte verwalten',
	'Manage backups' => 'Backups verwalten',
	'Manage perms' => 'Rechte verwalten',      
	'marked' => 'markierte',
	'Modify' => 'Bearbeiten',
	'Modify dropleps' => 'Dropleps bearbeiten',   
 	'Name' => 'Name',       
 	'No Backups found' => 'Keine Backups gefunden',
 	'No Dropleps found' => 'Keine Dropleps gefunden',
 	'No valid Droplep file (missing description and/or usage instructions)' => 'Kein valides Droplep (weder Beschreibung noch Angaben zur Verwendung vorhanden)',  
	'Packaging error' => 'Fehler beim Packen',
	'Permissions' => 'Rechte',
	'Permissions saved' => 'Rechte gespeichert',   
	'Please check the syntax!' => 'Bitte die Syntax &uuml;berpr&uuml;fen!',
	'Please choose a file' => 'Bitte eine Datei ausw&auml;hlen',  
	'Please enter a name!' => 'Bitte einen Namen eingeben!',
	'Please mark some Dropleps to delete' => 'Bitte Droplep(s) zum L&ouml;schen markieren',
	'Please mark some Dropleps to export' => 'Bitte einige Dropleps zum Export markieren',
	'Save and Back' => 'Speichern und zur&uuml;ck',  
 	'Search' => 'Suche',         
 	'Size' => 'Dateigr&ouml;sse',
	'Successfully imported [{{count}}] Droplep(s)' => '[{{count}}] Droplep(s) erfolgreich importiert',  
 	'The Droplep was saved' => 'Droplep gespeichert',
	'Unable to delete droplep: {{id}}' => 'Fehler beim L&ouml;schen von Droplep: {{id}}',
	'Upload failed'	=> 'Upload fehlgeschlagen',
	'Use' => 'Verwendung',
	'Valid' => 'Valide',
	'View groups' => 'Dieses Droplep benutzen',        
 	'You have entered no code!' => 'Es wurde kein Code eingegeben!',
 	'You dont have the permission to do this' => 'Keine Berechtigung'  

);

?>