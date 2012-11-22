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

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: ../../index.php'));

global $admin;

$table = TABLE_PREFIX .'mod_droplets';
$database->query("DROP TABLE IF EXISTS `$table`");

$database->query("CREATE TABLE `$table` (
	`id` INT NOT NULL auto_increment,
	`name` VARCHAR(32) NOT NULL,
	`code` LONGTEXT NOT NULL ,
	`description` TEXT NOT NULL,
	`modified_when` INT NOT NULL default '0',
	`modified_by` INT NOT NULL default '0',
	`active` INT NOT NULL default '0',
	`admin_edit` INT NOT NULL default '0',
	`admin_view` INT NOT NULL default '0',
	`show_wysiwyg` INT NOT NULL default '0',
	`comments` TEXT NOT NULL,
	PRIMARY KEY ( `id` )
	)"
);

//add all droplets from the droplet subdirectory
$folder=opendir(WB_PATH.'/modules/droplets/example/.'); 
$names = array();
while ($file = readdir($folder)) {
	$ext=strtolower(substr($file,-4));
	if ($ext==".php"){
		if ($file<>"index.php" ) {
			$names[count($names)] = $file; 
		}
	}
}
closedir($folder);

foreach ($names as $dropfile) {
	$droplet = addslashes(getDropletCodeFromFile($dropfile));
	if ($droplet != "") {
		$description = "Example Droplet";
		$comments = "Example Droplet";
		$cArray = explode("\n",$droplet);
		if (substr($cArray[0],0,3) == "//:") {
			$description = trim(substr($cArray[0],3));
			array_shift ( $cArray );
		}
		if (substr($cArray[0],0,3) == "//:") {
			$comments = trim(substr($cArray[0],3));
			array_shift ( $cArray );
		}
		$droplet = implode ( "\n", $cArray );
		$name = substr($dropfile,0,-4);
		$modified_when = time();
		$modified_by = method_exists($admin, 'get_user_id') ? $admin->get_user_id() : 1;
		$database->query("INSERT INTO `$table`  
			(name, code, description, comments, active, modified_when, modified_by) 
			VALUES 
			('$name', '$droplet', '$description', '$comments', '1', '$modified_when', '$modified_by')");
		
		// do not output anything if this script is called during fresh installation
		if ( !defined('LEPTON_INSTALL') ) echo "Droplet import: $name<br/>";
	}  
}

function getDropletCodeFromFile ( $dropletfile ) {
	$data = "";
	$filename = WB_PATH."/modules/droplets/example/".$dropletfile;
	if (file_exists($filename)) {
		$filehandle = fopen ($filename, "r");
		$data = fread ($filehandle, filesize ($filename));
		fclose($filehandle);
		// unlink($filename); doesnt work in unix
	}	
	return $data;
}

// import and installl additional droplets zip
if (file_exists(WB_PATH . '/modules/droplets/example/droplet_check-css.zip')) {
include_once (WB_PATH . '/modules/droplets/functions.inc.php');
wb_unpack_and_import(WB_PATH . '/modules/droplets/example/droplet_check-css.zip', WB_PATH . '/temp/unzip/');
}

// import and installl additional droplets zip
if (file_exists(WB_PATH . '/modules/droplets/example/droplet_year.zip')) {
include_once (WB_PATH . '/modules/droplets/functions.inc.php');
wb_unpack_and_import(WB_PATH . '/modules/droplets/example/droplet_year.zip', WB_PATH . '/temp/unzip/');
}

// delete additional droplets zip after installation
$temp_path = WB_PATH."/modules/droplets/example/droplet_check-css.zip";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}

$temp_path = WB_PATH."/modules/droplets/example/droplet_year.zip";
if (file_exists($temp_path)) {
	$result = unlink ($temp_path);
	if (false === $result) {
		echo "Cannot delete file ".$temp_path.". Please check file permissions and ownership or delete file manually.";
	}
}
?>