<?php

/**
 *	This file is part of LEPTON Core, released under the GNU GPL
 *	Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 *	NOTICE:LEPTON CMS Package has several different licenses.
 *	Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *	@author			LEPTON Project
 *	@copyright		2010-2011, LEPTON Project
 *	@link			http://www.LEPTON-cms.org
 *	@license		http://www.gnu.org/licenses/gpl.html
 *	@license_terms	please see LICENSE and COPYING files in your package
 *	@version	0.1.0
 *	@build		2
 *	@date		2012-11-15
 *	@state		alpha
 *	@package	LEPTON-CMS - Framework
 *	@platform	1.2.1
 *	@require	PHP 5.x
 *
 */
 
class lepton_validate_request
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
	
	public function __construct ( &$options="" ) {
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
	 *	i	int		integer		Any integer
	 *	i+	int+	integer+	Any positive integer
	 *	i-	int-	integer-	Any negative integer
	 *	s	str		string		Any string
	 *
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
	 *	char	default: ' ' (space) if #fill is used and the number of chars is less than the min-value,
	 *			the string is 'filled' up with this char.
	 *			default: ' ' (space|blank)
	 *
	 *	If one of the keys/options is missing, the default-settings are used.
	 *	NOTICE:	the range-settings have no effekt if the default-value is set to NULL.
	 *
	 */

	public function get_request( &$aName="", $aDefault=NULL, &$type="", &$range="" ) {
		
		if ($aName == "") return NULL;
		
		if ( false === $this->strict_looking_inside ) {
		
			if ( strtoupper($_SERVER['REQUEST_METHOD']) == "POST") {
				$return_value = (true === array_key_exists ($aName , $_POST ) ) ? $_POST[$aName] : $aDefault;
			} else {
				$return_value = (true === array_key_exists ($aName , $_GET ) ) ? $_GET[$aName] : $aDefault;
			}
		} else {
			switch (strtolower($this->strict_looking_inside)) {
				case 'post':
					$return_value = (true === array_key_exists ($aName , $_POST ) ) ? $_POST[$aName] : $aDefault;
					break;
					
				case 'get':
					$return_value = (true === array_key_exists ($aName , $_GET ) ) ? $_GET[$aName] : $aDefault;
					break;
					
				case 'request':
					$return_value = (true === array_key_exists ($aName , $_REQUEST ) ) ? $_GET[$aName] : $aDefault;
					break;
					
				default:
					$return_value = NULL;
					break;
			}
		}
		
		if ($type != "") {
			switch (strtolower($type)) {
				case 'i':
				case 'int':
				case 'integer':
					$return_value = (integer) $return_value;
					if (!is_integer($return_value) || !is_numeric($return_value)) {
						$return_value = $aDefault;
					} else {
						if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					}
					break;
				
				case 'i+':
				case 'int+':
				case 'integer+':
					$return_value = (integer) $return_value;
					if (!is_integer($return_value)) $return_value = $aDefault;
					if ( $return_value < 0) $return_value = $aDefault;
					if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					break;
					
				case 'i-':
				case 'int-':
				case 'integer-':
					$return_value = (integer) $return_value;
					if (!is_integer($return_value)) $return_value = $aDefault;
					if ( $return_value > 0) $return_value = $aDefault;
					if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					break;
				
				case 's':
				case 'str':
				case 'string':
					$return_value = (string) $return_value;
					if (!is_string($return_value)) $return_value = $aDefault;
					if ( true === is_array($range) ) $this->__check_range($type, $return_value, $aDefault, $range);
					break;
					
				case 'ean':
					if (false === $this->validate_ean13($return_value)) $return_value = $aDefault;
					break;
			}
		}
		return $return_value;	
	}
	
	static public function add_slash (&$s="") {
		if (substr($s, 0,1) != "/") $s = "/".$s;
		if (substr($s, -1) != "/") $s .= "/";
	}
	
	private function __check_range ($type, &$value, &$default, &$range) {
		
		if ($value === NULL) return true;
		
		if (!array_key_exists('use', $range)) $range['use'] = 'default';
		if (!array_key_exists('min', $range)) $range['min'] = 0;
		if (!array_key_exists('max', $range)) $range['max'] = 255;
		if (!array_key_exists('char', $range)) $range['char'] = " ";
		
		switch (strtolower ($type) ) {
			case 'i':
			case 'int':
			case 'int+':
			case 'integer':
			case 'integer+':
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
				
			case 's':	
			case 'str':
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
	
	public function get_ean13_control_number ($aEAN_str="") {
		
		$this->__clean_str($aEAN_str);
		
		if (strlen($aEAN_str) != 12) return false;
		
		return $this->calculate_controlNumber( $aEAN_str );
	}
	
	/**
	 *	More or less obsolete: transform an ISBN 10 to an ISBN 13
	 *	incl. recalculating the control-number.
	 *	Since Jan. 2007 there are no new ISBN 10 Numbers given.
	 *
	 *	@notice:	Please keep in mind, that there could be also a "979" leading first!
	 *
	 */
	public function isbn10_to_isbn13 ($aISBN10_str, $aAddition='978') {
		
		$aStr = str_replace ("x", "0", strtolower($aISBN10_str));
		
		$this->__clean_str($aStr);
		
		$cStr = $aAddition.substr($aStr, 0, -1); 
		
		return $aAddition."-".substr($aISBN10_str, 0, -1).$this->calculate_controlNumber ( $cStr );
	}
	
	/**
	 *	Also more or less obsolete.
	 *	(For barcode prepare)
	 *	Please keep in mind that you can't restore the company-number, nor the product-id after that!
	 */
	public function isbn13_to_ean13 ($aISBN13_str, $aChar = "") {
		
		$this->__clean_str($aISBN13_str);
		
		$temp = array ();
		$temp[] = substr($aISBN13_str, 0, 1);
		$temp[] = substr($aISBN13_str, 1, 6);
		$temp[] = substr($aISBN13_str, 7, 6);
		
		return implode($aChar, $temp);
	}
	
	public function validate_isbn13 ( $aISBN13_str ) {
		return $this->validate_ean13( $aISBN13_str );
	}
	
	public function validate_ean13 ($aEAN13_str) {
		
		$this->__clean_str($aEAN13_str);
		
		if (strlen($aEAN13_str) != 13) return false;
		
		$sum = $this->calculate_controlNumber ( $aEAN13_str );
		
		return ($sum == 0);
	}
	
	/**
	 *	Private function that remove all non-digits 
	 *
	 *	@param	string	Any string (EAN/ISBN/etc).
	 *	@return	nothing	Argument pass by reference
	 *
	 *	e.g.	"3 - 1234 - 1234 -5"	-> "3123412345"
	 *			"9-78000-0000-0"		-> "978000000000"
	 *			"0.1234.1234.7"			-> "0123412347"
	 */
	private function __clean_str (&$aStr) {
		//$aStr = str_replace(array (' ', '-', '–'), "", $aStr);
		
		$pattern = array ("/[\D]{1,}/");
		$replace = array ("");
		
		$aStr = preg_replace($pattern, $replace, $aStr);
	}
	
	/**
	 *
	 *	EAN		odd=1	even=3	mod=10
	 *	ISBN	odd=1	evne=3	mod=10
	 *	ISMN	odd=3	even=1	mod=10	* leading M must calculate as 3
	 *	DP/DHL	odd=4	even=9	mod=10	(Deutsche Post - DHL identcode)
	 *
	 */
	public function calculate_controlNumber ( $aStr = "", $odd=1, $even=3, $mod=10 ) {
		
		$this->__clean_str($aStr);
		
		$n = strlen($aStr);
		
		for($i=0, $sum=0, $wrap=-1; $i<$n; $i++, $wrap *= -1)
			$sum += intval(substr($aStr, $i, 1) ) * ($wrap==1 ? $even : $odd );
		
		return ($mod - ( $sum % $mod)) % $mod;
	}
	
	public function got_error () {
		return ( count($this->errors) > 0);
	}
	
	private function __add_error($aNum=0, $aType="", $aMessage="") {
		$this->errors[] = array ('num' => $aNum, 'type' => $aType, 'msg' => $aMessage);
	}
	
	public function test_NULL(&$aArray, $aNewLocation) {
		foreach($aArray as $item=>$value) if ($value === NULL) die( header("Location: ".$aNewLocation) );
	}
}
/**
 *	'my_integer' => array ('type' => 'int+', 'default' => 0, 'range' => array ('min' => 0, 'max'=>255, 'use' => 'min'|'max'|'near'|'default'))
 *	'my_string' => array ('type' => 'str', 'default' => "", 'range' => array ('min' => 3, 'max'=>255, 'use' => 'default'|'fill'|'cut', 'char' => " "))
 *
 *
 */
?>