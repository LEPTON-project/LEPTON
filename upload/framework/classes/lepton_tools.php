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
 
class LEPTON_tools
{
	/**
	 *	Static method to return the result of a "printr" call for a given object/address.
	 * 
	 *	@param	mixed 	Any (e.g. mostly an object instance, or e.g. an array
	 *	@param	string	Optional a "tag" (-name). Default is "pre".
	 *	@param	string	Optional a class name for the tag.
	 *
	 *	E.g.
	 *		LEPTON_tools::display( $result_array, "code", "example_class" )
	 *
	 *		will return e.g.
	 *		<code class="example_class">
	 *			array( [1] => "whatever");
	 *		</code>
	 */
	static function display( $something_to_display ="", $tag="pre", $css_class=NULL ) {
	
		$s = "\n<".$tag.( NULL === $css_class ? "" : " class='".$css_class."'").">\n";
		ob_start();
			print_r( $something_to_display );
		$s .= ob_get_clean();
		$s .= "\n</".$tag.">\n";
	
		return $s;
	}
	
	/**
	 *	Static method to "require" a (LEPTON-) internal function file 
	 *
	 *	@param	string	A function name, and/or an array with function-names
	 *
	 *	e.g.
	 *		//	one single function
	 *		LEPTON_tools::register( "bind_jquery" );
	 *
	 *		//	a set of functions
	 *		LEPTON_tools::register( "get_menu_title", "isValidMd5", "js_alert_encode" );
	 *
	 *		//	a set of function names inside an array
	 *		$all_needed= array("get_menu_title", "isValidMd5", "js_alert_encode" );
	 *		LEPTON_tools::register( $all_needed, "rm_full_dir" );
	 *
	 */
	static function register() {
		
		if( 0 === func_num_args() ) return false;
		
		$all_args = func_get_args();
		foreach($all_args as &$param) {
			
			if( true === is_array($param) ) {
				foreach($param as $ref) self::register( $ref );
			} else {
			
				if(!function_exists($param)) {
					$lookUpPath = LEPTON_PATH."/framework/functions/function.".$param.".php";
					if(file_exists($lookUpPath)) require_once($lookUpPath);
				}
			}
		}
	}
	
	/**
	 *	Try to "require" one or more local files.
	 *
	 *	@param	mixed	A (local) filepath, a set of paths and/or an array with paths
	 *
	 */
	static function load() {
		
		if( 0 === func_num_args() ) return false;
		
		$all_args = func_get_args();
		foreach($all_args as &$param) {
			if(true === is_array( $param ) ) {
				foreach( $param as $ref) self::load( $ref );
			} else {
				if ( file_exists($param) ) {
					require_once( $param );
				} else {
					echo "\n<pre class='ui message'>\nCan't include: ".$param."\n</pre>\n";
				}
			}
		}
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
?>
