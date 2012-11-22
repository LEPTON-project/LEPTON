<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2012, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {
	include(WB_PATH.'/framework/class.secure.php');
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

$LANG = array(
 	'Actions' => 'Aktionen',
 	'Back to overview' => 'Zur&uuml;ck zur &Uuml;bersicht',
 	'Backup file deleted: {{file}}' => 'Backup Datei gel&ouml;scht: {{file}}',
 	'Contained files' => 'Enthaltene Dateien',
 	'Date' => 'Erstelldatum',
 	'Files' => 'Dateien',
 	'List Backups' => 'Backups auflisten',
 	'Manage backups' => 'Backups verwalten',
 	'No Backups found' => 'Keine Backups gefunden',
 	'No Dropleps found' => 'Keine Dropleps gefunden',
 	'Size' => 'Dateigr&ouml;sse',
 	'The Droplep was saved' => 'Droplep gespeichert',
 	'You have entered no code!' => 'Es wurde kein Code eingegeben!',
	'An error occurred when trying to import the Droplep(s)' => 'Beim Import ist ein Fehler aufgetreten',
	'Backup created' => 'Backup erzeugt',
	'Delete' => 'L&ouml;schen',
	'Duplicate' => 'Kopieren',
	'Export' => 'Exportieren',
	'Import' => 'Importieren',
	'Invalid' => 'Nicht valide',
	'marked' => 'markierte',
	'Modify' => 'Bearbeiten',
	'Packaging error' => 'Fehler beim Packen',
	'Please check the syntax!' => 'Bitte die Syntax &uuml;berpr&uuml;fen!',
	'Please enter a name!' => 'Bitte einen Namen eingeben!',
	'Please mark some Dropleps to delete' => 'Bitte Droplep(s) zum L&ouml;schen markieren',
	'Please mark some Dropleps to export' => 'Bitte einige Dropleps zum Export markieren',
	'Successfully imported [{{count}}] Droplep(s)' => '[{{count}}] Droplep(s) erfolgreich importiert',
	'Unable to delete droplep: {{id}}' => 'Fehler beim L&ouml;schen von Droplep: {{id}}',
	'Use' => 'Verwendung',
	'Valid' => 'Valide',
	'Groups' => 'Gruppen',
	'Permissions' => 'Rechte',
	'Droplep permissions' => 'Droplep Rechte',
	'Manage permissions' => 'Rechte verwalten',
	'Permissions saved' => 'Rechte gespeichert',
	'Manage global permissions' => 'Globale Rechte verwalten',
	'Manage Droplep permissions' => 'Droplep Rechte verwalten',
	'Edit datafile' => 'Datendatei bearbeiten',
 	'Create new' => 'Neues Droplep',
 	'Description' => 'Beschreibung',
 	'Active' => 'Aktiv',
 	'Search' => 'Suche',
 	'Droplep is NOT registered in Search' => 'Das Droplep ist NICHT f&uuml;r die Suche aktiv',
 	'Droplep is registered in Search' => 'Das Droplep ist für die Suche aktiv',
 	'No valid Droplep file (missing description and/or usage instructions)' => 'Kein valides Droplep (weder Beschreibung noch Angaben zur Verwendung vorhanden)',
	// ----- permissions -----
	'add_dropleps' => 'Dropleps hinzuf&uuml;gen',
	'modify_dropleps' => 'Dropleps bearbeiten',
	'manage_backups' => 'Backups verwalten',
	'manage_perms' => 'Rechte verwalten',
	'export_dropleps' => 'Dropleps exportieren',
	'import_dropleps' => 'Dropleps importieren',
	'delete_dropleps' => 'Dropleps l&ouml;schen',
	'edit_groups' => 'Dieses Droplep bearbeiten',
	'view_groups' => 'Dieses Droplep benutzen',
	
);

?>