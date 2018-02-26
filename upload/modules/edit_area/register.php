<?php

/**
 * @module   	    edit_area
 * @version        	see info.php of this module
 * @author			Christophe Dolivet (EditArea), Christian Sommer (wrapper), LEPTON Project
 * @copyright		2009-2010 Christian Sommer
 * @copyright       2010-2018 LEPTON Project
 * @license        	GNU General Public License
 * @license terms  	see info.php of this module
 * @platform       	see info.php of this module
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

if (!function_exists('registerEditArea'))
{
	function registerEditArea(
                $id = 'code_area',
                $syntax = 'php',
                $syntax_selection = true,
                $allow_resize = 'both',
                $allow_toggle = true,
                $start_highlight = true,
                $min_width = 600,
                $min_height = 300,
                $toolbar = 'default'
				)
	{

		// set default toolbar if no user defined was specified
		if ($toolbar == 'default') {
			$toolbar = 'search, fullscreen, |, undo, redo, |, select_font, syntax_selection, |, highlight, reset_highlight, |, help';
			$toolbar = (!$syntax_selection) ? str_replace('syntax_selection,', '', $toolbar) : $toolbar;
		}

		// check if used Website Baker backend language is supported by EditArea
		$language = 'en';
		if (defined('LANGUAGE') && file_exists(dirname(__FILE__) . '/langs/' . strtolower(LANGUAGE) . '.js'))
        {
			$language = strtolower(LANGUAGE);
		}

		// check if highlight syntax is supported by edit_area
		$syntax = in_array($syntax, array('css', 'html', 'js', 'php', 'xml','csv','sql')) ? $syntax : 'php';

		// check if resize option is supported by edit_area
		$allow_resize = in_array($allow_resize, array('no', 'both', 'x', 'y')) ? $allow_resize : 'no';

		/**
		 *	Try to load the basic js only one time.
		 */
		$return_value = "";
		if (!defined('EDIT_AREA_LOADED')) {
			define('EDIT_AREA_LOADED', true);
			$script_url = LEPTON_URL.'/modules/edit_area/edit_area/edit_area_full.js';
			$return_value .= "\n<script src='".$script_url."' type='text/javascript'></script>\n";
		}
		
        $data = array(
            'id'        => $id,
            'min_width' => $min_width,
            'min_height' => $min_height,
            'allow_resize'  => $allow_resize,
            'allow_toggle'  => $allow_toggle,
            'toolbar'       => $toolbar,
            'language'      => $language,
            'syntax'        => $syntax,
            'start_highlight'   => $start_highlight
        );

        $oTwig = lib_twig_box::getInstance();
        $oTwig->registerModule("edit_area");
        
		$return_value .= $oTwig->render(
		    "@edit_area/register.lte",
		    $data
		);	
		
		return $return_value;
	}
}

if (!function_exists('getEditAreaSyntax')) {
	function getEditAreaSyntax($file) 
	{
	    // to be backward compatible - no idea where on eath this function is been calling from. 
		return edit_area::getEditAreaSyntax( $file );
	}
}

?>