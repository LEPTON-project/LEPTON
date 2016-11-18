<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

	require_once ( LEPTON_PATH . '/framework/functions/function.is_registered_droplet.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.register_droplet.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.unregister_droplet.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.droplet_exists.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.get_droplet_headers.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.get_addon_page_title.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.get_addon_page_description.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.get_addon_page_keywords.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.register_addon_header.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.unregister_addon_header.php' );
	
	require_once ( LEPTON_PATH . '/framework/functions/function.unregister_addon_header.php' );

	?>