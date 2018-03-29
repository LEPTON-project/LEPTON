<?php

/**
 * @module          Cookie
 * @author          cms-lab
 * @copyright       2017-2018 cms-lab
 * @link            http://www.cms-lab.com
 * @license         custom license: http://cms-lab.com/_documentation/cookie/license.php
 * @license_terms   see: http://cms-lab.com/_documentation/cookie/license.php
 *
 */
 
 /**
 * All cookie javascript files are created by 
 * https://cookieconsent.insites.com/
 * and are licensed under MIT
 * https://cookieconsent.insites.com/documentation/license/
 * 
 * The used color-picker is created by
 * https://tovic.github.io/color-picker/
 * and is also licensed under MIT: https://opensource.org/licenses/MIT
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

$module_directory     = "cookie";
$module_name          = "Cookie";
$module_function      = "tool";
$module_version       = "3.0.6.4";
$module_platform      = "4.x";
$module_author        = '<a href="http://cms-lab.com" target="_blank">CMS-LAB</a>';
$module_license       = '<a href="http://cms-lab.com/_documentation/cookie/license.php" class="info" target="_blank">Custom license</a>';
$module_license_terms = '<a href="http://cms-lab.com/_documentation/cookie/license.php" class="info" target="_blank">License terms</a>';
$module_description   = "Tool to get users informed about cookies.";
$module_guid		  = "d7a7c31e-a197-45a4-9131-66297f0c0cc8";

?>