<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author		LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license		http://www.gnu.org/licenses/gpl.html
 * @license_terms	please see LICENSE and COPYING files in your package
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

/**
 *	Class to handle values out the $_POST or $_GET
 */
 
class c_validate_request
{

	/**
	 *	Public var to handle the way the class should look for the value;
	 *	supported types are 'post', 'get' or 'request'
	 *
	 *	@since		0.1.0
	 *	@type		bool or string
	 *	@default	false
	 *
	 */
	public $strict_looking_inside = false;
	
	/**
	 *	By default we are not collecting any errors
	 *	
	 *	@since		0.1.0
	 *	@type		bool
	 *	@default	false
	 *
	 */
	public $error_mode = false;
	
	/**
	 *	Public var that holds the errors in an array.
	 *
	 *	@since		0.1.0
	 *	@type		array
	 *	@default	empty array
	 *
	 */
	public $errors = array();
	
	public function __construct(&$options="") {
		if ( true === is_array($options)) {
			if (true === array_key_exists("strict_looking_inside", $options)) 
				$this->strict_looking_inside == strtolower($options['strict_looking_inside']);
		}
	}
	
	/**
	 *
	 *	Public function that looks for a value in the POST or GET super-variables.
	 *	If it is found and the type match it is returnd. If not, the given default
	 *	value is returned. There are also optional settings for the situation, the
	 *	value has to be in a given rangen, and/or the string has to be a given size.
	 *
	 *	@param	string	name	Name of the value/key
	 *	@param	mixed	default	(optional) The default of this value
	 *	@param	string	type	(optional) The type of the value, see list below
	 *	@param	array	range	(optional) The settings for testing if the value is inside a range.
	 *
	 *	@retuns	mixed	the value
	 *
	 *	NOTICE:	the range-settings have no effekt if the default-value is set to NULL.
	 *
	 *
	 *	Supported types are (quick-, short-, long-notation)
	 *	
	 *	integer		Any integer
	 *	integer+	Any positive integer + 0
	 *	integer-	Any negative integer +0
	 *	string		Any string
	 *	email		Any mail adress
	 *	array		Any array (e.g. from a multible select)
	 *
	 *	Range got following keys/options
	 *
	 *	min		the minmium, as for integers the lowes number, as for strings the min. number of chars.
	 *			default: 0;
	 *
	 *	max		the maximum, as for integers the heights number, as for strings the max. number of chars.
	 *			default: 255;
	 *
	 *	use		what kind of use if the value is not inside the range, supported types are
	 *			- default	use the given default value (integer, string, NULL)
	 *			- min		however (value is less or more out of the range) use the min-value.
	 *			- max		however (value is less or more out of the range) use the max-value.
	 *			- near		if the value is less, use the min-value, if it is more, use the max-value.
	 *			- fill		only for strings.
	 *			- cut		only for strings.
	 *			default: 'default'
	 *
	 *	char	default: ' 'if #fill is used and the number of chars is less than the min-value,
	 *			the string is 'filled' up with this char.
	 *			default: ' ' (space|blank)
	 *
	 *	If one of the keys/options is missing, the default-settings are used.
	 *	NOTICE:	the range-settings have no effekt if the default-value is set to NULL.
	 *
	 */
	 
	public function get_request(&$aName="", $aDefault=NULL, &$type="", &$range="") {
		
		if ($aName == "") return NULL;
		
		if ( false === $this->strict_looking_inside ) {
		
			if ( strtoupper($_SERVER['REQUEST_METHOD']) == "POST") {
				$return_value = (true === array_key_exists ($aName , $_POST) ) ? $_POST[$aName] : $aDefault;
			} else {
				$return_value = (true === array_key_exists ($aName , $_GET) ) ? $_GET[$aName] : $aDefault;
			}
		} else {
			switch (strtolower($this->strict_looking_inside)) {
				case 'post':
					$return_value = (true === array_key_exists($aName , $_POST) ) ? $_POST[$aName] : $aDefault;
					break;
					
				case 'get':
					$return_value = (true === array_key_exists($aName , $_GET) ) ? $_GET[$aName] : $aDefault;
					break;
					
				case 'request':
					$return_value = (true === array_key_exists($aName , $_REQUEST) ) ? $_GET[$aName] : $aDefault;
					break;
					
				default:
					$return_value = NULL;
					break;
			}
		}
		
		if ($type != "") {
			switch (strtolower($type)) {
				case 'integer':
					$return_value = (integer) $return_value;
					if (!is_integer($return_value)) {
						$return_value = $aDefault;
					} else {
						if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					}
					break;
				
				case 'integer+':
					$return_value = (integer) $return_value;
					if (!is_integer($return_value)) $return_value = $aDefault;
					if ($return_value < 0) $return_value = $aDefault;
					if (true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					break;
					
				case 'integer-':
					$return_value = (integer) $return_value;
					if (!is_integer($return_value)) $return_value = $aDefault;
					if ( $return_value > 0) $return_value = $aDefault;
					if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					break;
				
				case 'string':
					//	keep in mind that pdo add slashes automatically	via prepare and execute
					if (!is_string($return_value)) $return_value = $aDefault;
					if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					break;
					
				case 'ean':
					if (false === $this->validate_ean13($return_value)) $return_value = $aDefault;
					break;
					
				case 'email':
					if (!filter_var($return_value, FILTER_VALIDATE_EMAIL)) {
						$return_value = '';
					}
					break;	

				case 'array':
					if(!is_array($return_value)) $return_value = $aDefault;
					break;
			}
		}
		return $return_value;
		
	}
	
	static public function add_slash( &$s="" ) {
		if (substr($s, 0,1) != "/") $s = "/".$s;
		if (substr($s, -1) != "/") $s .= "/";
	}
	
	private function __check_range($type, &$value, &$default, &$range) {
		
		if ($value === NULL) return true;
		
		if ( !array_key_exists('use', $range)) $range['use'] = 'default';
		if ( !array_key_exists('min', $range)) $range['min'] = 0;
		if ( !array_key_exists('max', $range)) $range['max'] = 255;
		if ( !array_key_exists('char', $range)) $range['char'] = " ";
		
		switch (strtolower ($type) ) {
			case 'integer':
			case 'integer+':
			case 'integer-':			
				if ( ($value < $range['min']) OR ($value > $range['max']) ) {
					switch (strtolower($range['use'])) {
						case 'default' : $value = $default; break;
						case 'min': $value = $range['min']; break;
						case 'max': $value = $range['max']; break;
						case 'near':
							if ($value < $range['min']) $value = $range['min'];
							if ($value > $range['max']) $value = $range['max'];
							break;
					}
				}
				break;
				
			case 'string':
				$nc = strlen($value);
				
				if (($nc < $range['min']) OR ($nc > $range['max'])) {
					
					switch(strtolower($range['use'])) {
						case 'default': $value = $default; break;
						case 'fill':
							for ($i=$nc; $i<=$range['min'];$i++) $value .= $range['char'];
							break;
						case 'cut':
							$value = substr($value, 0, $range['max']);
							break;
					}
				}
				break;
		}
		return true;
	}
}
?>