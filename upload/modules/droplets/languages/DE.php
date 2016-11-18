<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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
 	'Actions' => 'Aktionen',
 	'Active' => 'Aktiv',
	'Add_droplets' => 'Droplets hinzuf&uuml;gen',    
	'An error occurred when trying to import the Droplet(s)' => 'Beim Import ist ein Fehler aufgetreten',
	'Are you sure'	=> 'Sind Sie sicher, das Sie das droplet \n\n»%s«\n\nlöschen wollen?\nDas kann nicht widerufen werden!!',
 	'Backup file deleted: {{file}}' => 'Backup Datei gel&ouml;scht: {{file}}',
	'Backup created' => 'Backup erzeugt', 
 	'Back to overview' => 'Zur&uuml;ck zur &Uuml;bersicht',   
 	'Contained files' => 'Enthaltene Dateien',
 	'Create new' => 'Neues Droplet',  
 	'Date' => 'Erstelldatum',
	'Delete' => 'L&ouml;schen',
	'Delete_droplets' => 'Droplets l&ouml;schen',  
 	'Description' => 'Beschreibung',
 	'Droplet is NOT registered in Search' => 'Das Droplet ist NICHT f&uuml;r die Suche aktiv',
 	'Droplet is registered in Search' => 'Das Droplet ist f&uuml;r die Suche aktiv',    
	'Droplet permissions' => 'Droplet Rechte',  
	'Duplicate' => 'Kopieren',
	'Edit datafile' => 'Datendatei bearbeiten',
	'Edit droplet'	=> 'Droplet bearbeiten',  
	'Edit_perm' => 'Dieses Droplet bearbeiten',    
	'Export' => 'Exportieren',
	'Export_droplets' => 'Droplets exportieren',        
 	'Files' => 'Dateien',
	'Groups' => 'Gruppen',  
	'Import' => 'Importieren',
	'Import_droplets' => 'Droplets importieren',  
	'Invalid' => 'Nicht valide',    
 	'List Backups' => 'Backups auflisten',
 	'Manage_backups' => 'Backups verwalten',
	'Manage permissions' => 'Rechte verwalten',
	'Manage global permissions' => 'Globale Rechte verwalten',
	'Manage Droplet permissions' => 'Droplet Rechte verwalten',
	'Manage backups' => 'Backups verwalten',
	'Manage_perms' => 'Rechte verwalten',      
	'marked' => 'markierte',
	'Modify' => 'Bearbeiten',
	'Modify_droplets' => 'Droplets bearbeiten',   
 	'Name' => 'Name',       
 	'No Backups found' => 'Keine Backups gefunden',
 	'No droplets found' => 'Keine droplets gefunden',
 	'No valid Droplet file (missing description and/or usage instructions)' => 'Kein valides Droplet (weder Beschreibung noch Angaben zur Verwendung vorhanden)',  
	'Packaging error' => 'Fehler beim Packen',
	'Permissions' => 'Rechte',
	'Permissions saved' => 'Rechte gespeichert',   
	'Please check the syntax!' => 'Bitte die Syntax &uuml;berpr&uuml;fen!',
	'Please choose a file' => 'Bitte eine Datei ausw&auml;hlen',  
	'Please enter a name!' => 'Bitte einen Namen eingeben!',
	'Please mark some droplets to delete' => 'Bitte Droplet(s) zum L&ouml;schen markieren',
	'Please mark some droplets to export' => 'Bitte Droplets zum Export markieren',
	'Save and Back' => 'Speichern und zur&uuml;ck',  
 	'Search' => 'Suche',         
 	'Size' => 'Dateigr&ouml;sse',
	'Successfully imported [{{count}}] Droplet(s)' => '[{{count}}] Droplet(s) erfolgreich importiert',  
 	'The Droplet was saved' => 'Droplet gespeichert',
	'Unable to delete droplet: {{id}}' => 'Fehler beim L&ouml;schen von Droplet: {{id}}',
	'Upload failed'	=> 'Upload fehlgeschlagen',
	'Use' => 'Verwendung',
	'Valid' => 'Valide',
	'view_perm' => 'Dieses Droplet benutzen',        
 	'You have entered no code!' => 'Es wurde kein Code eingegeben!',
 	'You dont have the permission to do this' => 'Keine Berechtigung'  

);

?>