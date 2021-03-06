<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_page_footers
 * @author          LEPTON Project
 * @copyright       2012-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
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
 * get additions for page footer (js, script)
 *
 * + gets all active sections for a page;
 * + scans module directories for file footers.inc.php;
 * + includes that file if it is available
 * + includes automatically if exists:
 *   + module dirs:
 *     + frontend.css / backend.css              (media: all)
 *     + frontend_print.css / backend_print.css  (media: print)
 *   + template dir:
 *     + <PAGE_ID>.css in template dir           (media: all)
 *
 * @access public
 * @param  string  $for - 'frontend' (default) / 'backend'
 * @return void (echo's result)
 *
 **/
function get_page_footers( $for = 'frontend' )
{
	global $FOOTERS;
	
	// don't do this twice
	if ( defined( 'LEP_FOOTERS_SENT' ) )
	{
		return;
	}
	
	//  Initialize var with an empty string.
	$module = '';
	
	if ( !$for || $for == '' || ( $for != 'frontend' && $for != 'backend' ) )
	{
		$for = 'frontend';
	}

	$page_id = defined( 'PAGE_ID' ) ? PAGE_ID : ( ( isset( $_GET[ 'page_id' ] ) && is_numeric( $_GET[ 'page_id' ] ) ) ? $_GET[ 'page_id' ] : NULL );
	if ($page_id === NULL) {
		if ( (isset($_POST['page_id'])) && (is_numeric( $_POST[ 'page_id' ] ))) {
			$page_id = $_POST['page_id'];
		}
	}
	
	$js_subdirs = array();
		
	// it's an admin tool...
	if ( $for == 'backend' && isset( $_REQUEST[ 'tool' ] ) && file_exists( LEPTON_PATH . '/modules/' . $_REQUEST[ 'tool' ] . '/tool.php' ) )
	{
		$module = $_REQUEST[ 'tool' ];
		$js_subdirs[] = array(
			'modules/' . $_REQUEST[ 'tool' ] . '/js',
			'modules/' . $_REQUEST[ 'tool' ]
		);
		if ( file_exists( LEPTON_PATH . '/modules/' . $_REQUEST[ 'tool' ] . '/footers.inc.php' ) )
		{
			addItems( $for, LEPTON_PATH . '/modules/' . $_REQUEST[ 'tool' ], true );
		}
	}
	elseif ( $page_id && is_numeric( $page_id ) )
	{
		$sections = get_active_sections( $page_id, NULL, ($for === "backend") );
		if ( is_array( $sections ) && count( $sections ) )
		{
			global $current_section;
			
			// avoid loading modules twice!
			$loaded_modules = array();
			
			foreach ( $sections as $section )
			{
				
				$module = $section[ 'module' ];
				
				if(in_array( $module, $loaded_modules))
				{
					continue;
				}
				$loaded_modules[] = $module;
				
				// find header definition file
				if ( file_exists( LEPTON_PATH . '/modules/' . $module . '/footers.inc.php' ) )
				{
					$current_section = $section[ 'section_id' ];
					addItems( $for, LEPTON_PATH . '/modules/' . $module , true);
					
				}
				
				$temp_js = array(
					'modules/' . $module,
					'modules/' . $module . '/js' 
				);
				
				if ( $for == 'frontend' )
				{

					global $oLEPTON;
					$current_template = $oLEPTON->page['template'] != "" ? $oLEPTON->page['template'] : DEFAULT_TEMPLATE;
					$lookup_file = "templates/".$current_template."/frontend/".$module;
					
					$temp_js[] = $lookup_file;
					$temp_js[] = $lookup_file."/js";
			
				} // end $for == 'frontend' 
				else {
					// start $for == 'backend' 
					$current_theme = DEFAULT_THEME;
					$lookup_file = "templates/".$current_theme."/backend/".$module;
					
					$temp_js[] = $lookup_file;
					$temp_js[] = $lookup_file."/js";
					
					// end $for == 'backend' 
				}
				
				$js_subdirs[]= array_reverse($temp_js);
			}
		}
		// add css/js search subdirs for frontend only; page based CSS/JS
		// does not make sense in BE
		if ( $for == 'frontend' )
		{
			$js_subdirs[] = array(
				PAGES_DIRECTORY,
				PAGES_DIRECTORY . '/js'
			);
		}
	}
	
	// add template JS
	// note: defined() is just to avoid warnings, the NULL does not really
	// make sense!
	$subdir = ( $for == 'backend' ) 
		? ( defined( 'DEFAULT_THEME' ) ? DEFAULT_THEME : NULL ) 
		: ( defined( 'TEMPLATE' ) ? TEMPLATE : NULL )
		;
	
	if($module != '')
	{
	    if(!isset($loaded_modules))
	    {
            
            $js_subdirs[0][] = 'templates/' . $subdir . '/backend/'.$module;
            $js_subdirs[0][] = 'templates/' . $subdir . '/backend/'.$module.'/js';
            $js_subdirs[0]= array_reverse($js_subdirs[0]);
        }
	}
	
	$js_subdirs[] = array(
        'templates/' . $subdir . '/js', 
        'templates/' . $subdir
	);

    // echo LEPTON_tools::display($js_subdirs);
    
	// automatically add JS files
	foreach ( $js_subdirs as $first_level_dir )
	{
		foreach($first_level_dir as $directory)
		{
            $file = $directory . '/' . $for . '_body.js';
				
            if ( file_exists( LEPTON_PATH . '/' . $file ) )
            {
                $FOOTERS[ $for ][ 'js' ][] = $file;
                break;
			}
		}
	}
	
	$output = '';
	
	foreach ( array(
		 'js',
		'script' 
	) as $key )
	{
		if ( !isset( $FOOTERS[ $for ][ $key ] ) || !is_array( $FOOTERS[ $for ][ $key ] ) )
		{
			continue;
		}
		
		foreach ( $FOOTERS[ $for ][ $key ] as $i => $arr )
		{
			switch ( $key )
			{
				case 'js':
					$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/' . $arr . '"></script>' . "\n";
					break;
				case 'script':
					$output .= '<script type="text/javascript">' . implode( "\n", $arr ) . '</script>' . "\n";
					break;
				default:
					trigger_error( 'Unknown footer type [' . $key . ']!', E_USER_NOTICE );
					break;
			}
		}
	}
	
	define( 'LEP_FOOTERS_SENT', true );
	
	if ( $for =='frontend') 
	{
		echo $output;
	
	} else 
	{
		return $output;
	}
}
?>