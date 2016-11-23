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

	static function display( $something_to_display ="", $tag="pre", $css_class=NULL ) {
	
		$s = "\n<".$tag.( NULL === $css_class ? "" : " class='".$css_class."'").">\n";
		ob_start();
			print_r( $something_to_display );
		$s .= ob_get_clean();
		$s .= "\n</".$tag.">\n";
	
		return $s;
	}
	
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
