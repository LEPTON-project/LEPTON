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

// delete all tables
LEPTON_handle::drop_table("mod_cookie");
?>