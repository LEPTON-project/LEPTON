<?php

/**
 *  @template       LEPSem
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2018 cms-lab
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
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


$mod_headers = array(
	'backend' => array(
        'css' => array(
		array(
			'media'  => 'all',
			'file'  => 'modules/lib_semantic/dist/semantic.min.css'
			),
		array(
			'media'  => 'all',
			'file'  => 'modules/lib_jquery/jquery-ui/jquery-ui.min.css'
			),
		array(
			'media'  => 'all',
			'file'  => 'modules/lib_jquery/jquery-ui/themes/smoothness/jquery-ui.theme.min.css'
			)			
 		),				
		'js' => array(
			'modules/lib_jquery/jquery-core/jquery-core.min.js',
			'modules/lib_jquery/jquery-core/jquery-migrate.min.js',
			'modules/lib_jquery/external/jquery.tablednd.min.js',
			'modules/lib_jquery/jquery-ui/jquery-ui.min.js',
			'modules/lib_semantic/dist/semantic.min.js'
		),
	)
);

?>