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
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *  This is only an abstract class for Lepton specific classes.
 *
 */
abstract class LEPTON_class
{
    
	/*
	 *	@var Singleton The reference to *Singleton* instance of this class
	 */
	static $instance;

	/**
	 *	Return the instance of this class
	 *
	 */
	public static function getInstance()
	{
		if (null === static::$instance)
		{
			static::$instance = new static();
			static::$instance->initialize();
		}
		return static::$instance;
	}

	abstract protected function initialize();
	
}