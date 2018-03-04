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
    
    /**
     *  Generates the HTML-code to display the edit-area wysiwyg editor, incl. the textarea.
     *
     *  @param  string  $name       The name(-attribute) of the generated textarea.
     *  @param  string  $id         The id(-attribute) of the generated textarea.
     *  @param  string  $content    The content of the generated textarea.
     *  @param  string  $width      Optional the width of the editor. Default is "100%".
     *  @param  string  $height     Optional the height of the editor. Default is "350px".
     *  @param  bool    $prompt     Optional: direct echo the result or return the generated source? Default is true.
     *
     *  @return mixed   Bool true if prompt, otherwise string (the generated HTML_code)
     *
     */
	static function show_wysiwyg_editor($name, $id, $content, $width = '100%', $height = '350px', $prompt=true) { 
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
		
		$HTML_source = $oTwig->render(
			'@edit_area/show.lte',
			$data
		);
		
		if(true === $prompt)
		{
		    echo $HTML_source;
		    return true;
		} else {
		    return $HTML_source;
		}
	}	
	
	/**
	 *  Returns the generated JS code (script-tag) for EditArea incl. the link-tag to edit-area source.
	 *
	 *  @param string   $id                 The ID if the refrended textarea.
     *  @param string   $syntax             The syntax to displax: defailt is 'php'.
     *  @param bool     $syntax_selection   Default is 'true'.
     *  @param string   $allow_resize       Default is 'both'.
     *  @param bool     $allow_toggle       Default is 'true'.
     *  @param bool     $start_highlight    Default is 'true'.
     *  @param integer  $min_width          The min width (in px), default is 600.
     *  @param integer  $min_height         The min height (in px), default si 300.
     *  @param string   $toolbar            The toolbar(name or definition). The default is 'default'.
     *
     *  @return string  The generated js-script tag.
	 */
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