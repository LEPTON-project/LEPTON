<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
class LEPTON_tools
{
	/**
	 *	To use "var_dump" instead of "print_r" inside the "display"-method.
	 *
	 *	@property bool	For the use of 'var_dump'.
	 */
	static public $use_var_dump = false;
	
	/**
	 *	Method to change the var_dump_mode
	 *
	 *	@param	boolean	True, to use "var_dump" instead of "print_r" for the "display"-method, false if not. Default is "true".
	 *	@see	display
	 *
	 */
	static public function use_var_dump( $bUseVarDump=true )
	{
		self::$use_var_dump = (bool) $bUseVarDump;
	}
	
	/**
	 *	Method to return the result of a "print_r" call for a given object/address.
	 * 
	 *	@param	mixed 	Any (e.g. mostly an object instance, or e.g. an array)
	 *	@param	string	Optional a "tag" (-name). Default is "pre".
	 *	@param	string	Optional a class name for the tag.
	 *
	 *	example given:
	 *	@code{.php}
	 *		LEPTON_tools::display( $result_array, "code", "example_class" )
	 *	@endcode
	 *		will return:
	 *	@code{.xml}
	 *
	 *		<code class="example_class">
	 *			array( [1] => "whatever");
	 *		</code>
	 *
	 *	@endcode
	 *
	 */
	static function display( $something_to_display ="", $tag="pre", $css_class=NULL ) {
	
		$s = "\n<".$tag.( NULL === $css_class ? "" : " class='".$css_class."'").">\n";
		ob_start();
			(true === self::$use_var_dump)
			? var_dump( $something_to_display )
			: print_r( $something_to_display )
			;
		$s .= ob_get_clean();
		$s .= "\n</".$tag.">\n";
	
		return $s;
	}
	
	/**
	 *	Returns the given leptoken hash
	 *
	 *	@param	none 
	 *	@return string	The given Leptoken-Hash or an empty string
	 *
	 */
	static function get_leptoken() {
		$leptoken = "";
		if(isset($_POST['leptoken'])) {
			$leptoken = $_POST['leptoken'];
		} elseif (isset($_GET['leptoken'])) {
			$leptoken = $_GET['leptoken'];
		} elseif ( isset($_GET['amp;leptoken']) ) {
			$leptoken = $_GET['amp;leptoken'];
		}
		
		return $leptoken;
	}
}
