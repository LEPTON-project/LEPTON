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
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
class LEPTON_core
{
	
	/**
	 *	@var Singleton The reference to *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 *	Return the »internal«
	 *
	 *	@param	array	Optional params
	 */
	public static function getInstance( &$settings=array() )
	{
		if (null === static::$instance)
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 *		Get the date formats
	 */
	static function get_dateformats() {
	
		global $user_time;
		global $TEXT;
		
		// Get the current time (in the users timezone if required)
		$actual_time = time();

		// Get "System Default"
		$sSystemDefault = "";
		if ( isset( $user_time ) && $user_time == true )
		{
			$sSystemDefault = date( DEFAULT_DATE_FORMAT, $actual_time ) . ' (';
			$sSystemDefault .= ( isset( $TEXT[ 'SYSTEM_DEFAULT' ] ) ? $TEXT[ 'SYSTEM_DEFAULT' ] : 'System Default' ) . ')';
		}

		// Add values to list
		$DATE_FORMATS = array(
			'system_default' => $sSystemDefault,
			'j.n.Y' => date( 'j.n.Y', $actual_time ) . ' (j.n.Y)',
			'm/d/Y' => date( 'm/d/Y', $actual_time ) . ' (M/D/Y)',
			'd/m/Y' => date( 'd/m/Y', $actual_time ) . ' (D/M/Y)',
			'm.d.Y' => date( 'm.d.Y', $actual_time ) . ' (M.D.Y)',
			'd.m.Y' => date( 'd.m.Y', $actual_time ) . ' (D.M.Y)',
			'm-d-Y' => date( 'm-d-Y', $actual_time ) . ' (M-D-Y)',
			'd-m-Y' => date( 'd-m-Y', $actual_time ) . ' (D-M-Y)' /*,
			'D M d, Y' => date( 'D M d, Y', $actual_time ),
			'M d Y' => date( 'M d Y', $actual_time ),
			'd M Y' => date( 'd M Y', $actual_time ),
			'jS F, Y' => date( 'jS F, Y', $actual_time ),
			'l, jS F, Y' => date( 'l, jS F, Y', $actual_time ) */
		);

		$oDateTool = lib_lepton::getToolInstance("datetools");
		$oDateTool->set_core_language( DEFAULT_LANGUAGE );
		
		$aFormatList = array(
			'D M d, Y',
			'M d Y',
			'd M Y',
			'jS F, Y',
			'l, jS F, Y'
		);
		
		foreach( $aFormatList as &$format ) {
			$oDateTool->format = $oDateTool->CORE_date_formats[ $format ];
			$DATE_FORMATS[ $format ] =  $oDateTool->toHTML();
		}
		
		return $DATE_FORMATS;
	}
	
	/**
	 *		Get the time formats
	 */
	static function get_timeformats() {
	
		global $user_time;
		global $TEXT;
		
		// Get the current time (in the users timezone if required)
		$actual_time = time();

		// Get "System Default"
		$sSystemDefault = "";
		
		if ( isset( $user_time ) AND $user_time == true )
		{
			$sSystemDefault = date( DEFAULT_TIME_FORMAT, $actual_time ) . ' (';
			$sSystemDefault .= ( isset( $TEXT[ 'SYSTEM_DEFAULT' ] ) ? $TEXT[ 'SYSTEM_DEFAULT' ] : 'System Default' ) . ')';
		}

		// Store the values array
		$TIME_FORMATS = array(
	 		'system_default' => $sSystemDefault,
			'H:i' => date( 'H:i', $actual_time ),
			'H:i:s' => date( 'H:i:s', $actual_time ),
			'g:i a' => date( 'g:i a', $actual_time ),
			'g:i A' => date( 'g:i A', $actual_time ) 
		);
		
		return $TIME_FORMATS;
	}
	
	/**
	 *	Get the time zones
	 *
	 */
	static function get_timezones()
	{
	
		$timezone_table = array(
			 "Pacific/Kwajalein",
			"Pacific/Samoa",
			"Pacific/Honolulu",
			"America/Anchorage",
			"America/Los_Angeles",
			"America/Phoenix",
			"America/Mexico_City",
			"America/Lima",
			"America/Caracas",
			"America/Halifax",
			"America/Buenos_Aires",
			"Atlantic/Reykjavik",
			"Atlantic/Azores",
			"Europe/London",
			"Europe/Berlin",
			"Europe/Kaliningrad",
			"Europe/Moscow",
			"Asia/Tehran",
			"Asia/Baku",
			"Asia/Kabul",
			"Asia/Tashkent",
			"Asia/Calcutta",
			"Asia/Colombo",
			"Asia/Bangkok",
			"Asia/Hong_Kong",
			"Asia/Tokyo",
			"Australia/Adelaide",
			"Pacific/Guam",
			"Etc/GMT+10",
			"Pacific/Fiji" 
		);

		if ( !defined( "DEFAULT_TIMEZONESTRING" ) )
		{
			define( "DEFAULT_TIMEZONESTRING", "Europe/Berlin" );
		}
		
		return $timezone_table;
	}
	
	/**
	 *	Get the charsets
	 */
	static function get_charsets()
	{
	
		// Create array
		$CHARSETS = array(
			'utf-8'	=> 'Unicode (utf-8)',
			'iso-8859-1'	=> 'Latin-1 Western European (iso-8859-1)',
			'iso-8859-2'	=> 'Latin-2 Central European (iso-8859-2)',
			'iso-8859-3'	=> 'Latin-3 Southern European (iso-8859-3)',
			'iso-8859-4'	=> 'Latin-4 Baltic (iso-8859-4)',
			'iso-8859-5'	=> 'Cyrillic (iso-8859-5)',
			'iso-8859-6'	=> 'Arabic (iso-8859-6)',
			'iso-8859-7'	=> 'Greek (iso-8859-7)',
			'iso-8859-8'	=> 'Hebrew (iso-8859-8)',
			'iso-8859-9'	=> 'Latin-5 Turkish (iso-8859-9)',
			'iso-8859-10'	=> 'Latin-6 Nordic (iso-8859-10)',
			'iso-8859-11'	=> 'Thai (iso-8859-11)',
			'gb2312'		=> 'Chinese Simplified (gb2312)',
			'big5'			=> 'Chinese Traditional (big5)',
			'iso-2022-jp'	=> 'Japanese (iso-2022-jp)',
			'iso-2022-kr'	=> 'Korean (iso-2022-kr)'
		);
		
		return $CHARSETS;
	
	}
	
	/**
	 *	Get the error-levels
	 */	
	static function get_errorlevels()
	{	
		global $TEXT;
		// Create array
		$ER_LEVELS = array();

		// Add values to list
		if(isset($TEXT['SYSTEM_DEFAULT'])) {
			$ER_LEVELS[''] = $TEXT['SYSTEM_DEFAULT'];
		} else {
			$ER_LEVELS[''] = 'System Default';
		}
		$ER_LEVELS['6135'] = 'E_ALL^E_NOTICE';
		$ER_LEVELS['0'] = 'E_NONE'; // standard for productive use
		$ER_LEVELS['6143'] = 'E_ALL';
		//$ER_LEVELS['8191'] = htmlentities('E_ALL&E_STRICT'); // for programmers
		$ER_LEVELS['-1'] = 'E_EVERYTHING'; // highest level, standard from LEPTON 2.0.0

		return $ER_LEVELS;
	}
	
	
	/**
	 *	Backend-Theme specific language values
	 *
	 *	@param	string	Any valid keystring. Default is NULL.
	 *	@return string	The value if the key or a warning about the missing key.
	 *
	 *	@notice	You can use the method to "preload" the theme-language file passing NULL 
	 *			to load them where you need them!
	 */
	static function get_backend_translation( $sKey=NULL )
	{
		global $TEXT, $THEME;
		
		if(!isset($THEME))
		{
			/**
			 *	Backend-Theme can also have additional language-files
			 */
			if(file_exists( THEME_PATH."/languages/".LANGUAGE.".php" ))
			{
				require_once( THEME_PATH."/languages/".LANGUAGE.".php" );
			}
			elseif( file_exists( THEME_PATH."/languages/EN.php" ) )
			{
				require_once( THEME_PATH."/languages/".LANGUAGE.".php" );
			}
			else
			{
				// avoid errors and conflicts for non existing $THEME 
				$THEME = array();
			}
		}
		
		if( $sKey == NULL)
		{
			return "";
		}
		
		if( isset($THEME[ $sKey ]) )
		{
			return $THEME[ $sKey ];
		}
		elseif( isset($TEXT[ $sKey] ) )
		{
			return $TEXT[ $sKey];
		}
		else
		{
			return "** ".$sKey." (Key not found in Languages!)";
		}
	}
}
?>
