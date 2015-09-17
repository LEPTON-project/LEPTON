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


// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$database->query("DROP TABLE ".TABLE_PREFIX."mod_miniform");
$database->query("DROP TABLE ".TABLE_PREFIX."mod_miniform_data");


?>