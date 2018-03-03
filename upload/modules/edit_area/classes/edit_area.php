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

class edit_area extends LEPTON_abstract
{
    public static $sDefaultFileType = "php";
    
    static $instance;
    
    public function initialize()
    {
    
    }

	static function show_wysiwyg_editor($name, $id, $content, $width = '100%', $height = '350px') { 
		global $section_id, $page_id, $database, $preview;
		
		$syntax = 'php';
		$syntax_selection = true;
		$allow_resize = 'both';
		$allow_toggle = true;
		$start_highlight = true;
		$min_width = 600;
		$min_height = 300;
		$toolbar = 'default';

		// set default toolbar if no user defined was specified
		if ($toolbar == 'default') {
			$toolbar = 'search, fullscreen, |, undo, redo, |, select_font, syntax_selection, |, highlight, reset_highlight, |, help';
			$toolbar = (!$syntax_selection) ? str_replace('syntax_selection,', '', $toolbar) : $toolbar;
		}

		// check if used backend language is supported by EditArea
		$language = 'en';
		if (defined('LANGUAGE') && file_exists(dirname(__FILE__) . '/langs/' . strtolower(LANGUAGE) . '.js')) {
			$language = strtolower(LANGUAGE);
		}

		// check if highlight syntax is supported by edit_area
		$syntax = in_array($syntax, array('css', 'html', 'js', 'php', 'xml','csv')) ? $syntax : 'php';

		// check if resize option is supported by edit_area
		$allow_resize = in_array($allow_resize, array('no', 'both', 'x', 'y')) ? $allow_resize : 'no';
		
		/**
		 *	Try to load the basic js only one time.
		 */
		$register = "";
		if (!defined('EDIT_AREA_LOADED')) {
			define('EDIT_AREA_LOADED', true);
			$script_url = LEPTON_URL.'/modules/edit_area/edit_area/edit_area_full.js';
			$register .= "\n<script src='".$script_url."' type='text/javascript'></script>\n";
		}

		if (!isset($_SESSION['edit_area'])) {
			$script = LEPTON_URL.'/modules/edit_area/edit_area/edit_area_full.js';
			$register = "\n<script src=\"".$script."\" type=\"text/javascript\"></script>\n";

			if (!isset($preview)) {
				$last = $database->get_one("SELECT section_id from ".TABLE_PREFIX."sections where page_id='".$page_id."' order by position desc limit 1"); 
				$_SESSION['edit_area'] = $last;
			}

		} else {
			if ($section_id == $_SESSION['edit_area']) unset($_SESSION['edit_area']);
		}
		
		$data = array(
			'id'        => $id,
			'content'   => $content,
			'width'     => $width,
			'height'    => $height,
			'min_width' => $min_width,
			'min_height' => $min_height,
			'allow_resize'  => $allow_resize,
			'allow_toggle'  => $allow_toggle,
			'toolbar'       => $toolbar,
			'language'      => $language
		);
		
		$oTwig = lib_twig_box::getInstance();
		$oTwig->registerModule("edit_area");
		echo $oTwig->render(
			'@edit_area/show.lte',
			$data
		);
		
	}	
	
	
	static function registerEditArea(
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

		// check if used backend language is supported by EditArea
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