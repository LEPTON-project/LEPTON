<?php
/**
 *
 * @category        modules
 * @package         miniform
 * @author          Ruud Eisinga / erpe
 * @link			http://www.cms-lab.com
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        LEPTON 2.x
 * @home			http://www.cms-lab.com
 * @version         see info.php
 *
 *
 */


if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
	
// Create tables
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_miniform`");
$mod_miniform = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_miniform` ('
	. ' `section_id` INT NOT NULL DEFAULT \'0\','
	. ' `email` VARCHAR(128) NOT NULL DEFAULT \'\',' 
	. ' `subject` VARCHAR(128) NOT NULL DEFAULT \'\',' 
	. ' `template` VARCHAR(64) NOT NULL DEFAULT \'form\',' 
	. ' `successpage` INT NOT NULL DEFAULT \'0\',' 
	. ' PRIMARY KEY ( `section_id` ) '
	. ' )';
$database->query($mod_miniform);

$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_miniform_data`");
$mod_miniformdata = 'CREATE TABLE IF NOT EXISTS `'.TABLE_PREFIX.'mod_miniform_data` ('
	. ' `message_id` INT NOT NULL NOT NULL auto_increment,'
	. ' `section_id` INT NOT NULL DEFAULT \'0\','
	. ' `data` TEXT NOT NULL DEFAULT \'\',' 
	. ' `submitted_when` INT NOT NULL DEFAULT \'0\',' 
	. ' PRIMARY KEY ( `message_id` ) '
	. ' )';
$database->query($mod_miniformdata);
$path = WB_PATH.'/modules/miniform/';
if(file_exists($path.'new_frontend.css')) {
	if(!rename($path.'new_frontend.css',$path.'frontend.css')) {
		echo "<h2>Error renaming frontend.css. Please rename new_frontend.css manually to frontend.css</h2>";
	}
}

?>