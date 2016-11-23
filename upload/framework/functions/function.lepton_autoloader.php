<?php

/**
 *	LEPTON CMS autoloader 
 *
 *	e.g.
 *		LEPTON_tools::
 *		looking for class file inside framework/classes/
 *			here lepton_tools.php
 *
 *		TEMPLATE_aldus_scetchbook
 *			looking insde templates/aldus_scetchbook/classes/
 *
 *		display:code::getPaths()
 *			looking inside modules/display_code/classes/
 *			or:
 *			looking inside modules/display-code/classes/
 *
 */
 

function lepton_autoloader( $aClassName ) {

	$terms = explode("_", $aClassName);
	
	if( $terms[0] == "LEPTON" ) {
		$path = LEPTON_PATH."/framework/classes/".strtolower($aClassName).".php";
		if(file_exists($path)) require_once($path);
	
	} elseif( $terms[0] == "TEMPLATE" ) {
		array_shift($terms);
		$path = LEPTON_PATH."/templates/".( implode("_", $terms) )."/classes/".strtolower($aClassName).".php";
		if(file_exists($path)) require_once($path);
	
	} else {
	
		// assumee it is a "private" module specific CLASS
		
		$path = LEPTON_PATH."/modules/".$aClassName."/classes/".$aClassName.".php";
		if(file_exists($path)) {
			require_once($path);
		} else {
			$n = count($terms);
			$look_up = $terms[0];
			for( $i=1; $i< $n; $i++) {
				$temp_dir = $look_up."_".$terms[$i];
				$path = LEPTON_PATH."/modules/".$look_up."/classes/".$aClassName.".php";
				if(file_exists($path)) {
					require_once($path);
					break;
				} else {
					$temp_dir = $look_up."-".$terms[$i];
					$path = LEPTON_PATH."/modules/".$look_up."/classes/".$aClassName.".php";
					if(file_exists($path)) {
						require_once($path);
						break;
					}
				}
			}
		}
	}
}

?>