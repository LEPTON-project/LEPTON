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

$MOD_COOKIE = array(
	'allow'				=> "I agree",
	'allow_label'		=> "Button Allow",	
	'banner_background'	=> "Banner Color",
	'banner_text'		=> "Banner Text Color",
	'button_background'	=> "Button Color",
	'button_text'		=> "Button Text-Color",	
	'button_border'		=> "Button Border-Color",
	'dismiss'			=> "I accept!",
	'dismiss_label'		=> "Button Dismiss",
	'deny'				=> "I deny!",
	'deny_label'		=> "Button Deny",	
	'info'				=> "Addon Info",	
	'layout'			=> "Layout",
	'learn_more'		=> "Learn more",	
	'learn_more_label'	=> "Learn more link",		
	'message'			=> "This website uses cookies to ensure you get the best experience on our website.",
	'message_label'		=> "Information",	
	'overwrite'			=> "Overwrite language files (only single language sites)",
	'policy_name'		=> "Policy",
	'policy_link'		=> "Policy Link",
	'position'			=> "Position",
	'record_not_saved'	=> "Record was not saved!",		
	'record_saved'		=> "Record saved",	
	'type'				=> "Type",
	'type_text1'		=> "Just tell users that we use cookies",
	'type_text2'		=> "Let users opt out of cookies (Advanced)",
	'type_text3'		=> "Ask users to opt into cookies (Advanced)",
	'type_text_message1'	=> "Link for detailed infos</a>",	
	'type_text_message2'	=> "In case of 'advanced options' cookies are set referring to user action!"	
);



?>