<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          lib_lepton
 * @author          LEPTON Project
 * @copyright       2013-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          lib_lepton :: datetools
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */
class lib_lepton_datetools
{
	
	/**
	 *	Private var for the current version of this class.
	 *
	 *	@var	string
	 *	@access	public
	 *
	 */
	private	$version = "0.1.0.0";
	
	/**
	 *	The reference to *Singleton* instance of this class
	 *
	 *	@var	object
	 *	@access	private
	 *
	 */
	private static $instance;
	/**
	 *	The (default) format-string.
	 *	Default is dd.mm.yyyy
	 *	
	 *	@var	string
	 *	@access	public
	 *
	 */
	public $format = "%d.%m.%Y";
	
	/**
	 *	The language-flags as an array.
	 *	Default are some settings for German.
	 *	
	 *	@var	array
	 *	@access	public
	 *
	 */
	public $lang = Array('de_DE@euro', 'de_DE.UTF-8', 'de_DE', 'de', 'ge');
	
	/**
	 *	The mode we are using. Default is LC_ALL for all.
	 *	
	 *	@var string
	 *	@access	public
	 *
	 */
	public $mode = "LC_ALL";
	
	/**
	 *	Used for "_force_year" to determiante if the year belongs to 1900 or 2000.
	 *	Default setting is 2, so if the current year is 2008 a value of 10 will be force
	 *	to 2010 instead of 11 will be forece to 1911.
	 *
	 *	@var	integer
	 *	@see	_force_year
	 *	@access	public
	 *
	 */
	public $force_year = 2;
	/**
	 *	Translation-array for the LEPTON-CMS internal date-formats.
	 *
	 *	@var	array
	 *	@access	public
	 *
	 */
	public $CORE_date_formats = array(
		'l, jS F, Y'=> '%A, %e %B, %Y',
		'jS F, Y'	=> '%e %B, %Y',
		'd M Y'		=> '%d %a %Y',
		'M d Y'		=> '%a %d %Y',
		'D M d, Y'	=> '%a %b %d, %Y',	##
		'd-m-Y'	=> '%d-%m-%Y',	#1
		'm-d-Y'	=> '%m-%d-%Y',
		'd.m.Y'	=> '%d.%m.%Y',	#2
		'm.d.Y'	=> '%m.%d.%Y',
		'd/m/Y'	=> '%d/%m/%Y',	#3
		'm/d/Y' => '%m/%d/%Y',	
		'j.n.Y'	=> '%e.%n.%Y'	#4! Day in month without leading zero
	);
	/**
	 *	Translation-array for the LEPTON-CMS internal time-formats.
	 *
	 *	@var	array
	 *	@access	public
	 *
	 */	
	public $CORE_time_formats = array(
		'g:i A'	=> '%I:%M %p',
		'g:i a'	=> '%I:%M %P',
		'H:i:s'	=> '%H:%M:%S',
		'H:i'	=> '%H:%M'
	);
	
	/**
	 *	Return the »internal« instance of this class
	 *
	 */
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}
		return static::$instance;
	}
	/**
	 *	The constructor
	 */
	public function __construct () { 
	
	}
	
	/**
	 *	The destructor
	 */
	public function __destruct () { 
	
	}
	/**
	 *	Public function to add a Language-Setting-Flag.
	 *
	 *	Only added if not found inside the lang-array.
	 *
	 */
	public function addLanguage ($aString="") {
		if($aString == "") return false;
		if (false == in_array( $aString, $this->lang ) ) $this->lang[] = $aString;
		return true;
	}
	
	/**
	 *	Public function to set up the format-string
	 *
	 *	@param	string	The formatstring, even empty.
	 */
	public function setFormat ($aString="") {
		$this->format=$aString;
		return true;
	}
	
	/**
	 *	Public function to get the format-string.
	 *	
	 *	@param int		A valid Timestamp
	 *					If no timestamp is given the local time will be used.
	 *
	 *	@return	string	The formated date string
	 *	
	 *	@todo	Testing the timestamp before generating the html-return.
	 *
	 */
	public function toHTML ($aTimestamp=0 ) {
		if (0 == $aTimestamp) $aTimestamp = TIME();
		eval ("setLocale(".$this->mode. ",'".implode("','", $this->lang)."');" );
		return strftime($this->format, $aTimestamp);
	}
	
	/**
	 *	Public function to set up the language at once
	 *
	 *	@param	array	A simple Array with the strings
	 *
	 *	@return bool	Alwas true.
	 *
	 *	@todo	Testing the array-values and returning false
	 *			if something is not valid. How? No idea yet.
	 */
	public function setLanguage ($aArray = array() ) {
		$this->lang = array();
		foreach($aArray as $a) $this->lang[] =$a;
		
		return true;
	}
	
	/**
	 *	Public function to transform dd.mm.yyyy into the current format
	 *
	 *	@param	string	date in dd.mm.yyyy
	 *	@return	string	the formated string
	 *
	 *	Following date-delimiter are supported
	 *	"."		01.01.1971
	 *	"-"		01-01-1971
	 *	"/"		01/01/1971
	 *
	 *	Following format-settings are supported, including (space) and/or "." and/or "%".
	 *	'dmy'	day - month - year
	 *	'mdy'	month - day - year
	 *	'ymd'	year - month - day
	 *
	 */
	public function transform ($aDateString="01.01.1971", $aFormat="dmy") {
		
		$this->_force_date ($aDateString);
		$this->_force_format ($aFormat);
		
		$temp = explode(".", $aDateString);
		
		switch ($aFormat) {
			case 'dmy': 
				$this->_force_year($temp[2]);
				$temp_time = mktime( 1, 0, 0, $temp[1], $temp[0], $temp[2]);
				break;
				
			case 'mdy':
				$this->_force_year($temp[2]);
				$temp_time = mktime( 1, 0, 0, $temp[0], $temp[1], $temp[2]);
				break;
				
			case 'ymd':
				$this->_force_year($temp[0]);
				$temp_time = mktime( 1, 0, 0, $temp[1], $temp[2], $temp[0]);
				break;
				
			/**
			 *	M.f.i!
			 *
			 *	At this time (0.1.0.0) the default time is 
			 *	used instead of Error-Handling, thrown by an invalid format-string!
			 *
			 */
			default:
				$temp_time = time();
				break;
		}		
		return $this->toHTML($temp_time);	
	}
	
	/**
	 *	Private function to force a given string
	 *	into a internal dot-based format: "<s1>.<s2>.<s3>", where
	 *	"s(x)" stands for the sections (month, day, year).
	 *
	 *	@param	string	the Datestring
	 *	
	 *	@return	nothing	Param is passed by reference!
	 *
	 */	
	private function _force_date( &$aDateString ) {
		$pattern = array("*[\\/|.|-]{1,}*");
		$replace = array(".");
		
		$aDateString = preg_replace($pattern, $replace, $aDateString);
	}
	
	/**
	 *	Private function to force the format-string used
	 *	in/for "tranform"
	 *
	 *	@param	string	The transform-format-string - passed by reference!
	 *
	 *	@see 	transform
	 *
	 *	@todo	Error-Handling; the format-string has to have at 
	 *			least three chars: "d", "m", and "y"
	 *
	 */
	private function _force_format( &$aFormat ) {
		
		$aFormat = strtolower ($aFormat);
		
		$pattern = array("*[\\/|.|-]{1,}*", "*[ |%]{1,}*");
		$replace = array("", "");
		
		$aFormat = preg_replace($pattern, $replace, $aFormat);	
	}
	
	/**
	 *	private function that force a "short" Year to a "long" year
	 *
	 *	@param	string	the year - passed by reference!
	 *	@see	force_year
	 *
	 *	If the year is "futured" more than two years by default at runtime,
	 *	19xx is assumed.
	 */
	private function _force_year (&$aYearStr = "1971") {
		if (strlen($aYearStr) == 2)
			$aYearStr = ( ( (int)$aYearStr > $this->force_year + (int) DATE("y", TIME() ) ) ? "19" : "20").$aYearStr;
		if (strlen($aYearStr) > 4) $aYearStr = substr($aYearStr, 0,4);
	}
	
	/**
	 *	Public function to transform the date inside a given string
	 *
	 *	@param	string	The string within the dates. Pass by reference.
	 *	@param	string	Optional own patter/regexp for other formats.
	 *					default is "dd.mm.yyyy" e.g. 11.03.1966
	 */
	public function parse_string (&$aStr = "", $aPattern = "/([0-3][0-9].[01]{0,1}[0-9].[0-9]{2,4})/s" ) {
		$found=array();
		preg_match_all($aPattern, $aStr, $found );
		foreach ($found[1] as $a) {
			$aStr = str_replace($a, $this->transform($a), $aStr);
		}
	}
	
	/**
	 *	Setting up the language via a single key, 
	 *	e.g. inside LEPTON-CMS
	 *
	 *	@param	string	The language-key-str, e.g. "EN"...
	 *	@return	bool	true if the key is known, false if faild.
	 *
	 */
	public function set_core_language ($aKeyStr = "") {
		
		$return_value = true;
		
		switch ($aKeyStr) {
		
			case "DE":
				$this->lang = Array('de_DE@euro', 'de_DE.UTF-8', 'de_DE', 'de', 'ge');
				break;
			
			case "EN":
				$this->lang = Array('en_EN@euro', 'en_EN', 'en', 'EN', 'en_UK', 'UK', 'en_US', 'en_GB', 'en_CA');
				break;
			
			case "FR":
				$this->lang = Array('FR', 'fr_FR.UTF-8', 'fr_FR', 'fr_FR@euro', 'fr');
				break;
			case "IT":
				$this->lang = Array('it_IT@euro', 'it_IT', 'it');
				break;
			
			case "NL":
				$this->lang = Array('nl_NL@euro', 'nl_NL', 'nl', 'Dutch', 'nld_nld');
				break;
			case "RU":
				$this->lang = Array('RU', 'ru_RU.UTF-8', 'ru_RU', 'ru_RU@euro', 'ru');
				break;
			case "ZH":
				$this->lang = Array('zh_CN','zh_CN.eucCN','zh_CN.GB18030','zh_CN.GB2312','zh_CN.GBK','zh_CN.UTF-8','zh_HK','zh_HK.Big5HKSCS','zh_HK.UTF-8','zh_TW','zh_TW.Big5','zh_TW.UTF-8');
				break;
				
			default:
				$this->test_locale($aKeyStr);
		}
		return $return_value;
	}
	
	/**
	 *	Public function to test a given Languagekey
	 *	against the server-implantated ones using "locale -a".
	 *	If one or more are found the internal "lang" will be set.
	 *
	 *	@param	string	the LanguagKey, e.g. "EN", "fr_FR"
	 *					If only two chars are given, the rest will be 
	 *					automaticly formated as "uu_LL".
	 *
	 *	@param	bool	If the key is found - use it inside the class.
	 *
	 *	@return	array	all maches; could be empty.
	 *
	 */
	public function test_locale ($aKey = "de_DE", $use_it=true) {
		if (strlen($aKey) == 2) $aKey = strtolower($aKey)."_".strtoupper($aKey);
		
		$temp_array = array();
		ob_start();
			exec('locale -a', $temp_array);
		ob_end_flush();
		$all = array();
		
		foreach($temp_array as $lang_key) 
			if (substr($lang_key, 0,5) == $aKey) $all[]=$lang_key;
		
		if ( (count($all) > 0) AND (true == $use_it) ) $this->lang = $all;
		
		return $all;
	}
}

?>