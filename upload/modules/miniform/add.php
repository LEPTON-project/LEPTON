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
require_once (WB_PATH.'/framework/summary.functions.php');

// Insert an extra row into the database
$database->query("INSERT INTO ".TABLE_PREFIX."mod_miniform (`section_id`) VALUES ('$section_id')");

?>