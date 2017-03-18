<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module			Twig Template Engine
 * @author			LEPTON Project
 * @copyright		2012-2017 LEPTON  
 * @link			https://www.LEPTON-cms.org
 * @license			http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see info.php of this module
 *
 */

class lib_twig
{
	/**
	 * Registers Twig_Autoloader as an SPL autoloader.
	 *
	 * @param bool $prepend Whether to prepend the autoloader or not.
	 */
	public static function register($prepend = false)
	{
		spl_autoload_register(array(__CLASS__, 'autoload'), true, $prepend);
	}

	/**
	 * Handles autoloading of classes.
	 *
	 * @param string $class A class name.
	 */
	public static function autoload($class)
	{
		if (0 !== strpos($class, 'Twig'))
		{
			return;
		}

		$lookup = str_replace(
			array('_', "\0"),
			array('/', ''),
			$class
		);

		$file = dirname(dirname(__FILE__)).'/'.$lookup.'.php';

		if (is_file($file))
		{
			require $file;
		}
	}
}
?>