<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          lib_lepton
 * @author          LEPTON Project
 * @copyright       2013-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

class lib_lepton extends LEPTON_abstract
{
    public static $instance;
    
    public function initialize()
    {
    
    }

	/**
	 *	For (auto-)loading instances from sub-folders (tools) inside
	 *	the lib_lepton, e.g. 'pclzip', 'datetools', etc.
	 *	@code{.php}
	 *	$oUpload = lib_lepton::getToolInstance("upload", $_FILES[ $fileid ]);
	 *	@endcode	 
	 *	@param	string	A valid "toolname" of one of the sub-modules.
	 *	
	 *	@return mixed	A valid instance (object-reference) or NULL if failed or no name match.
	 *	 
	 */
	static public function getToolInstance($sToolName = "", $sParam2="")
	{
		$returnValue = NULL;
		
		switch(strtolower($sToolName))
		{
			case "datetools":
				require_once __dir__."/../datetools/lib_lepton_datetools.php";
				$returnValue = 	lib_lepton_datetools::getInstance();	
				break;
				
			case "pclzip":
				require_once __dir__."/../pclzip/pclzip.lib.php";
				$returnValue = new PclZip( $sParam2 );
				break;
			
			case "images":
				require_once __dir__."/../images/class.Images.php";
				$returnValue = new Image( $sParam2 );
				break;
				
			case "upload":
				require_once __dir__."/../upload/class.upload.php";
				$returnValue = new upload( $sParam2 );
				break;
		}
		
		return $returnValue;
	}
}