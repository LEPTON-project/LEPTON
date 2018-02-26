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
     * Returns the highlight scheme for edit_area
     *
     */
    public static function getEditAreaSyntax( $file = NULL ) 
	{
		if( NULL == $file)
		{
		    return self::$sDefaultFileType;
		} 
		
		if (is_readable($file)) {
			
			// extract file extension
			$file_info = pathinfo($file);
		
			switch ($file_info['extension']) {
				case 'htm':
				case 'html':
				case 'htt':
					$syntax = 'html';
	  				break;

	 			case 'css':
					$syntax = 'css';
	  				break;

				case 'js':
					$syntax = 'js';
					break;

				case 'xml':
					$syntax = 'xml';
					break;

	 			case 'php':
	 			case 'php4':
	 			case 'php5':
					$syntax = 'php';
	  				break;

				default:
					$syntax = self::$sDefaultFileType;
					break;
			}
		}
		return $syntax ;
	}
	
}