<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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

// references to objects and variables that changed their names
$admin = &$oLEPTON;

$default_link =& $oLEPTON->default_link;

$page_trail =& $oLEPTON->page_trail;
$page_description =& $oLEPTON->page_description;
$page_keywords =& $oLEPTON->page_keywords;
$page_link =& $oLEPTON->link;

$include_head_link_css = '';
$include_body_links    = '';
$include_head_links    = '';
// workout to included frontend.css, fronten.js and frontend_body.js in snippets
$query                 = "SELECT directory FROM " . TABLE_PREFIX . "addons WHERE type = 'module' AND function = 'snippet'";
$query_result          = $database->query( $query );
if ( $query_result->numRows() > 0 )
{
	while ( $row = $query_result->fetchRow() )
	{
		$module_dir = $row[ 'directory' ];
		if ( file_exists( LEPTON_PATH . '/modules/' . $module_dir . '/include.php' ) )
		{
			include( LEPTON_PATH . '/modules/' . $module_dir . '/include.php' );
			
			// check if frontend.css file needs to be included into the <head></head> of index.php			 
			if ( file_exists( LEPTON_PATH . '/modules/' . $module_dir . '/frontend.css' ) )
			{
				$include_head_link_css .= '<link href="' . LEPTON_URL . '/modules/' . $module_dir . '/frontend.css"';
				$include_head_link_css .= ' rel="stylesheet" type="text/css" media="screen" />' . "\n";
			}
			// check if frontend.js file needs to be included into the <body></body> of index.php
			if ( file_exists( LEPTON_PATH . '/modules/' . $module_dir . '/frontend.js' ) )
			{
				$include_head_links .= '<script src="' . LEPTON_URL . '/modules/' . $module_dir . '/frontend.js" type="text/javascript"></script>' . "\n";
			}
			// check if frontend_body.js file needs to be included into the <body></body> of index.php
			if ( file_exists( LEPTON_PATH . '/modules/' . $module_dir . '/frontend_body.js' ) )
			{
				$include_body_links .= '<script src="' . LEPTON_URL . '/modules/' . $module_dir . '/frontend_body.js" type="text/javascript"></script>' . "\n";
			}
		}
	}
}

// include function files
$file_names = array (
	"/framework/functions/function.page_link.php",
	"/framework/functions/function.get_page_link.php",
	"/framework/functions/function.page_content.php",
	"/framework/functions/function.page_title.php",
	"/framework/functions/function.page_description.php",
	"/framework/functions/function.page_keywords.php",
	"/framework/functions/function.page_header.php",
	"/framework/functions/function.page_footer.php",
	"/framework/functions/function.easymultilang_menu.php",
	"/framework/functions/function.search_highlight.php"
);
LEPTON_handle::include_files ($file_names);

?>