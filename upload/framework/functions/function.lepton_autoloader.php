<?php

/**
 *	LEPTON CMS autoloader 
 *
 *	e.g.
 *		LEPTON_tools::
 *		looking for class file inside framework/classes/
 *			here lepton_tools.php
 *
 *		TEMPLATE_aldus_scetchbook::
 *			looking insde templates/aldus_scetchbook/classes/
 *
 *		TEMPLATE_aldus_scetchbook_interface::
 *			looking insde templates/aldus_scetchbook/classes/ too!
 *
 *		display_code::getPaths()
 *			looking inside modules/display_code/classes/
 *			or:
 *			looking inside modules/display-code/classes/
 *
 */

function lepton_autoloader( $aClassName ) {
	$terms = explode("_", $aClassName);
	
	switch( $terms[0] ) {
		case 'LEPTON':
			//	We are looking inside the LEPTON-CMS framework directory:
			$path = LEPTON_PATH."/framework/classes/".strtolower($aClassName).".php";
			if(file_exists($path)) require_once $path;
			
			break;
	
		case "TEMPLATE":
			//	Class belongs to a frontendtemplate; we are looking inside the
			//	template directory:
			array_shift($terms);
			$class_filename = strtolower($aClassName).".php";
			$temp_dir = "";
			foreach($terms as &$term) {
				$temp_dir .= ($temp_dir === "") ? $term : "_".$term;
				$path = LEPTON_PATH."/templates/".$temp_dir."/classes/".$class_filename;
				if(file_exists($path)) {
					require_once $path;
					break;
				}
			}
			break;
			
		default:
			// suspected a "private" module specific CLASS
		
			$path = LEPTON_PATH."/modules/".$aClassName."/classes/".$aClassName.".php";
			if(file_exists($path)) {
				require_once($path);
			} else {
				$n = count($terms);
				$look_up = $terms[0];
				for( $i=1; $i< $n; $i++) {
					$temp_dir = $look_up."_".$terms[$i];
					$path = LEPTON_PATH."/modules/".$temp_dir."/classes/".$aClassName.".php";
					if(file_exists($path)) {
						require_once $path ;
						break;
					} else {
						$temp_dir = $look_up."-".$terms[$i];
						$path = LEPTON_PATH."/modules/".$temp_dir."/classes/".$aClassName.".php";
						if(file_exists($path)) {
							require_once $path;
							break;
						}
					}
				
					$look_up = $temp_dir;
				}
			}
			break;
	}
}

?>