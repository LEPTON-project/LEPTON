<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2014 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

$mod_headers = array();

if ( file_exists( LEPTON_PATH.'/modules/lib_jquery/plugins/fancybox' ) ) {
    $mod_headers = array(
		'backend' => array(
		    'css' => array(
				array(
					'media'		=> 'screen',
					'file'		=> '/modules/lib_jquery/plugins/fancybox/jquery.fancybox-1.3.1.css',
				)
			),
			'jquery' => array(
				array(
					'core'			=> false,
					'ui'			=> true,
					'ui-theme'		=> 'south_street',
				),
			),	
			'js' => array(
                '/modules/lib_jquery/plugins/fancybox/jquery.fancybox-1.3.1.pack.js',
			),
		),
	);
}

?>