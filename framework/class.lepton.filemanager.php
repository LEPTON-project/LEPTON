<?php

 /**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2012-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
class lepton_filemanager
{

	/**
	 *	Private array that holds the reg. filenames.
	 *
	 */
	private $filenames = array();
	
	/**
	 *	Constructor of the class
	 *
	 *	@param	mixed	String or array - within the filename(-s).
	 *
	 */
	public function __construct( $file_or_array = NULL) {
		if ($file_or_array != NULL)  $this->register_file( $file_or_array );
	}
	
	/**
	 *	Destructor of the class
	 *
	 */
	public function __destruct() {
		unset( $this->filenames );
	}
	
	/**
	 *	Public function/method to "register" files.
	 *
	 *	@param	mixed	Could be a single filename (string) or an array within the names.
	 *
	 */
	public function register($filename) {
		if (true === is_array($filename)) {
			$this->filenames = array_merge($filename, $this->filenames);
		} else {
			$this->filenames[] = $filename;
		}
	}
	
	/**
	 *	Public function/method to "unregister" files.
	 *	
	 *	@param	string	A single filename.
	 *
	 */
	public function unregister($filename) {
		if (in_array($filename, $this->filenames)) unset($this->filenames[$filename]);
	}
	
	/**
	 *	As the internal filename-array is private we have to merge it inside the class.
	 *
	 *	@param	array	Any array - pass by reference!
	 *
	 */
	public function merge_filenames(&$storrage=array()) {
		$storrage = array_merge($this->filenames, $storrage);
	}
	
	/**
	 *	Handel call for unkown methods/functions.
	 *
	 */
	public function __call($name, $arg_array) {
	
	}
}

global $lepton_filemanager;
if (!is_object($lepton_filemanager)) $lepton_filemanager = new lepton_filemanager();

?>