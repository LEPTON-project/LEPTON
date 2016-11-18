<?php

/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
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

$table = TABLE_PREFIX."mod_code2";

$all_jobs = array();


/**
 *	Creating the table new
 */
$query  = "CREATE TABLE IF NOT EXISTS `".$table."` (";
$query .= "`section_id`	INT NOT NULL DEFAULT '0',";
$query .= "`page_id`	INT NOT NULL DEFAULT '0',";
$query .= "`whatis`		INT NOT NULL DEFAULT '0',";
$query .= "`content`	TEXT NOT NULL,";
$query .= " PRIMARY KEY ( `section_id` ) )";

$all_jobs[] = $query;

/**
 *	Processing the jobs/querys all in once
 */
foreach( $all_jobs as $q ) {
	
	$database->execute_query( $q );
}

?>