<?php

/**
 * This file is part of an COREFILE for use with LEPTON Core.
 * This COREFILE is released under the GNU GPL.
 *
 * @file			class.lepton.filemanager.php
 * @author          LEPTON Project
 * @copyright       2010-2013, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 *
 */

class lepton_filemanager
{

	public $filenames = array();
	
	public function __construct() {
	
	}
	
	public function __destruct() {
	
	}
	
	public function register_file($filename) {
		if (true === is_array($filename)) {
			$this->filenames = array_merge($filename, $this->filenames);
		} else {
			$this->filenames[] = $filename;
		}
	}
	
	public function un_register_file($filename) {
		if (in_array($filename, $this->filenames)) unset($this->filenames[$filename]);
	}
}

global $lepton_filemanager;
$lepton_filemanager = new lepton_filemanager();

?>