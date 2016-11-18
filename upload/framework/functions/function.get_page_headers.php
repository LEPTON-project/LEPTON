<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_page_headers
 * @author          LEPTON Project
 * @copyright       2012-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
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
	 * get additions for page header (css, js, meta)
	 *
	 * + gets all active sections for a page;
	 * + scans module directories for file headers.inc.php;
	 * + includes that file if it is available
	 * + includes automatically if exists:
	 *   + module dirs:
	 *     + frontend.css / backend.css              (media: all)
	 *     + ./css/frontend.css / backend.css        (media: all)
	 *     + frontend_print.css / backend_print.css  (media: print)
	 *     + ./css/frontend_print.css / backend_print.css  (media: print)
	 *     + frontend.js / backend.js
	 *     + ./js/frontend.js / backend.js
	 *   + template dir:
	 *     + <PAGE_ID>.css 							 (media: all)
	 *     + ./css/<PAGE_ID>.css					 (media: all)
	 *   + pages_directory:
	 *     + <PAGE_ID>.css                           (media: all)
	 *     + ./css/<PAGE_ID>.css                     (media: all)
	 *
	 * @access public
	 * @param  string  $for - 'frontend' (default) / 'backend'
	 * @param  boolean $print_output
	 * @param  boolean $current_section
	 * @return void (echo's result)
	 *
	 **/
	function get_page_headers( $for = 'frontend', $print_output = true, $individual = false )
	{
		global $HEADERS;
		// don't do this twice
		if ( defined( 'LEP_HEADERS_SENT' ) )
		{
			return;
		} 
		if ( !$for || $for == '' || ( $for != 'frontend' && $for != 'backend' ) )
		{
			$for = 'frontend';
		} 
		$page_id = defined( 'PAGE_ID' ) ? PAGE_ID : ( ( isset( $_GET[ 'page_id' ] ) && is_numeric( $_GET[ 'page_id' ] ) ) ? $_GET[ 'page_id' ] : NULL );
		
		/**	*****************
		 *	Aldus: 2014-11-01
		 *	in some circumstances there is neither no page_id as constant nor inside the $_GET superglobal ...
		 *	In this case the module-css or -js files are not loaded.
		 *	So we are looking inside the $_POST superglobal ....
		 */
		if (NULL === $page_id) {
			if (isset($_POST['page_id']) && is_numeric($_POST['page_id'])) {
				$page_id = $_POST['page_id'];
			}
		}
		// end - Aldus
		// load headers.inc.php for backend theme
		if ( $for == 'backend' )
		{
			if ( file_exists( LEPTON_PATH . '/templates/' . DEFAULT_THEME . '/headers.inc.php' ) )
			{
				__addItems( $for, LEPTON_PATH . '/templates/' . DEFAULT_THEME );
			} 
		} 

		else
		{
			if ( file_exists( LEPTON_PATH . '/templates/' . DEFAULT_TEMPLATE . '/headers.inc.php' ) )
			{
				__addItems( $for, LEPTON_PATH . '/templates/' . DEFAULT_TEMPLATE );
			} 
		}
		
		// handle search
		/**
		 *	Aldus - 2014-11-10
		 *	Modifiy to get the css and js files from the frontend-template or the module itself
		 *	Even if we are not only displaying the search-results.
		 *	ToDo:	look for the search-settings itself. The block is useless if SHOW_SEARCH is false!
		 *
		 */

			// the page is called from the LEPTON SEARCH
			if ($for == "frontend") {
				$css_loaded = false;
				$js_loaded = false;
			
				global $wb;
				
				$current_template = $wb->page['template'] != "" ? $wb->page['template'] : DEFAULT_TEMPLATE;
				$lookup_file = "templates/".$current_template."/frontend/lib_search";
				
				foreach ( array(
					 $lookup_file,
					'modules/lib_search/templates' 
				) as $directory )
				{
					$file = $directory . '/' . $for . '.css';
					if ( file_exists( LEPTON_PATH . '/' . $file ) )
					{
						if(false === $css_loaded) {
							$HEADERS[ $for ][ 'css' ][] = array(
								'media' => 'all',
								'file' => $file 
							);
							$css_loaded = true;
						}
					}
					
					$file = $directory . '/' . $for . '.js';
					if ( file_exists( LEPTON_PATH . '/' . $file ) )
					{ 
						if (false === $js_loaded) {
							$HEADERS[ $for ][ 'js' ][] = $file;
							$js_loaded = true;
						}
					}
				}
						
				/**
				 *	Add css files for frontend-login, -preferences, -forgot-form etc.
				 */
				if (stripos($_SERVER['REQUEST_URI'], "/account/") !== FALSE ) {
					
					$lookup_files = array(
						"templates/".$current_template."/frontend/login/css/frontend.css",
						"account/css/frontend.css"
					);
					foreach($lookup_files as &$lookup_file) {
						if (file_exists(LEPTON_PATH."/".$lookup_file)) {
							$HEADERS['frontend']['css'][] = array(
								'media'	=> 'all',
								'file'	=> $lookup_file
							);
							break;
						}
					}
				}
			}

		
		// load CSS and JS for droplets
		if ( ( $for == 'frontend' ) && $page_id && is_numeric( $page_id ) )
		{
			if ( file_exists( LEPTON_PATH . '/framework/summary.droplets.php' ) )
			{
				require_once LEPTON_PATH . '/framework/summary.droplets.php';
				get_droplet_headers( $page_id );
			} 
		} 
		
		$css_subdirs = array();
		$js_subdirs  = array();
		
		// it's an admin tool...
		if ( $for == 'backend' && isset( $_REQUEST[ 'tool' ] ) && file_exists( LEPTON_PATH . '/modules/' . $_REQUEST[ 'tool' ] . '/tool.php' ) )
		{
			$css_subdirs[] = array(
				 '/modules/' . $_REQUEST[ 'tool' ],
				'/modules/' . $_REQUEST[ 'tool' ] . '/css' 
			);
			$js_subdirs[]  = array(
				 '/modules/' . $_REQUEST[ 'tool' ],
				'/modules/' . $_REQUEST[ 'tool' ] . '/js' 
			);
			if ( file_exists( LEPTON_PATH . '/modules/' . $_REQUEST[ 'tool' ] . '/headers.inc.php' ) )
			{
				__addItems( $for, LEPTON_PATH . '/modules/' . $_REQUEST[ 'tool' ] );
			} 
		} 
		
		// if we have a page id...
		elseif ( $page_id && is_numeric( $page_id ) )
		{
			// ... get active sections
			$sections = get_active_sections( $page_id, NULL, ($for === "backend") );
			
			if ( count( $sections ) )
			{
				global $current_section;
				global $mod_headers;
				
				//	local storage to avoid to load css/js twice
				$processed_modules = array();
				
				foreach ( $sections as $section )
				{
					$module       = $section[ 'module' ];
					
					if(in_array($module, $processed_modules)) {
						//	still processed
						continue;
					} else {
						$processed_modules[] = $module;
					}
					
					$headers_path = LEPTON_PATH . '/modules/' . $module;
					// special case: 'wysiwyg'
					if ( $for == 'backend' && !strcasecmp( $module, 'wysiwyg' ) )
					{
						// get the currently used WYSIWYG module
						if ( defined( 'WYSIWYG_EDITOR' ) && WYSIWYG_EDITOR != "none" )
						{
							$headers_path = LEPTON_PATH . '/modules/' . WYSIWYG_EDITOR;
						} 
					} 
					// find header definition file
					if ( file_exists( $headers_path . '/headers.inc.php' ) )
					{
						$current_section = $section[ 'section_id' ];
						__addItems( $for, $headers_path );
					} 
					else
					{
						/**
						 *	Aldus - 2014-11-02
						 *	Frontend - patch
						 */
						 global $wb;
						 if (is_object($wb)) {
						 	$current_template = $wb->page['template'] != "" ? $wb->page['template'] : DEFAULT_TEMPLATE;
						 	$lookup_file = LEPTON_PATH."/templates/".$current_template."/frontend/".$module;
						 	if (file_exists($lookup_file."/headers.inc.php")) {
						 		__addItems( $for,$lookup_file );
						 	}
						 }
						 // End Aldus
					}
					
					$temp_css = array(
						'modules/' . $module,
						'modules/' . $module . '/css' 
					);
					
					$temp_js = array(
						'modules/' . $module,
						'modules/' . $module . '/js' 
					);
			
					// add css/js search subdirs for frontend only; page based CSS/JS
					// does not make sense in BE
					if ( $for == 'frontend' )
					{
						// Aldus:
						$current_template = $wb->page['template'] != "" ? $wb->page['template'] : DEFAULT_TEMPLATE;
						$lookup_file = "templates/".$current_template."/frontend/".$module;
						
						$temp_css[] = $lookup_file;
						$temp_css[] = $lookup_file."/css";
						
						$temp_js[] = $lookup_file;
						$temp_js[] = $lookup_file."/js";
						
						// End Aldus
				
					} // $for == 'frontend' 
					else {
						// Aldus:
						$current_theme = DEFAULT_THEME;
						$lookup_file = "templates/".$current_theme."/backend/".$module;
						$temp_css[] = $lookup_file;
						$temp_css[] = $lookup_file."/css";
						
						$temp_js[] = $lookup_file;
						$temp_js[] = $lookup_file."/js";
						
						// End Aldus
					}
					
					$css_subdirs[]= array_reverse($temp_css);
					$js_subdirs[]= array_reverse($temp_js);
					
				} // foreach ($sections as $section)
			} // if (count($sections))
		} // if ( $page_id )
		
		// add template css
		// note: defined() is just to avoid warnings, the NULL does not really
		// make sense!
		$subdir = ( $for == 'backend' ) 
				? ( defined( 'DEFAULT_THEME' ) ? DEFAULT_THEME : NULL ) 
				: ( defined( 'TEMPLATE' ) ? TEMPLATE : NULL )
				;
		
		// automatically add CSS files
		/**
		 *	We are taking the first file (-link) we found.
		 *	Keep in mind that an optional additional css file in the frontend-template
		 *	is loaded INSTEAD of the module-internal one!
		 */
		foreach( $css_subdirs as $first_level_ref )
		{
			$css_found = false;
			$css_print_found = false;
			
			foreach( $first_level_ref as $directory )
			{
				// frontend.css / backend.css
				$file = $directory . '/' . $for . '.css';
				if ( file_exists( LEPTON_PATH . '/' . $file ) )
				{
					if ($css_found == false) {
						$HEADERS[ $for ][ 'css' ][] = array(
							'media' => 'all',
							'file' => $file 
						);
						$css_found = true;
					} 
				}
			
				// frontend_print.css / backend_print.css
				$file = $directory . '/' . $for . '_print.css';
				if ( file_exists( LEPTON_PATH . '/' . $file ) )
				{
					if ($css_print_found == false) {
						$HEADERS[ $for ][ 'css' ][] = array(
							'media' => 'print',
							'file' => $file 
						);
						$css_print_found = true;
					}
				}
			}
		}
		
		/**
		 *	Try to get a frontend "<page_id>.css" if there is one	
		 *
		 */
		if ( $for == 'frontend' ) {
			$current_template = $wb->page['template'] != "" ? $wb->page['template'] : DEFAULT_TEMPLATE;
			$lookup_files = array(
				"templates/".$current_template."/css/".$page_id.".css",
				"templates/".$current_template."/".$page_id.".css"
			);
			foreach($lookup_files as &$file) {
				if ( file_exists( LEPTON_PATH . '/' . $file ) ) {
					$HEADERS[ $for ][ 'css' ][] = array(
						'media' => 'all',
							'file' => $file 
					);
					break;
				}
			}
		}
			
		// Aautomatically add JS files
		
		foreach( $js_subdirs as &$first_level_ref )
		{
			$got_js = false;
			foreach( $first_level_ref as $directory )
			{
				$file = $directory . '/' . $for . '.js';
				if ( file_exists( LEPTON_PATH . '/' . $file ) )
				{
					if ($got_js == false) {
						$HEADERS[ $for ][ 'js' ][] = $file;
						$got_js = true;
					}
				}
			}
		}
		$output = null;
		foreach ( array( 'meta', 'css', 'jquery', 'js' ) as $key )
		{
			if ( !isset( $HEADERS[ $for ][ $key ] ) || !is_array( $HEADERS[ $for ][ $key ] ) )
			{
				continue;
			}
			
			foreach ( $HEADERS[ $for ][ $key ] as $i => $arr )
			{
				switch ( $key )
				{
					case 'meta':
						if ( is_array( $arr ) )
						{
							foreach ( $arr as $item )
							{
								$output .= $item . "\n";
							}
						}
						break;
						
					case 'css':
						// make sure we have an URI (LEPTON_URL included)
						$file = ( preg_match( '#' . LEPTON_URL . '#i', $arr[ 'file' ] ) ? $arr[ 'file' ] : LEPTON_URL . '/' . $arr[ 'file' ] );
						$output .= '<link rel="stylesheet" type="text/css" href="' . $file . '" media="' . ( isset( $arr[ 'media' ] ) ? $arr[ 'media' ] : 'all' ) . '" />' . "\n";
						break;
					case 'jquery':
						// make sure that we load the core if needed, even if the
						// author forgot to set the flags
						if ( ( isset( $arr[ 'ui' ] ) && $arr[ 'ui' ] === true ) || ( isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) ) || ( isset( $arr[ 'ui-components' ] ) && is_array( $arr[ 'ui-components' ] ) ) )
						{
						//	$arr[ 'core' ] = true; // take value true or false from headers.inc
						} //( isset( $arr[ 'ui' ] ) && $arr[ 'ui' ] === true ) || ( isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) ) || ( isset( $arr[ 'ui-components' ] ) && is_array( $arr[ 'ui-components' ] ) )
						// make sure we load the ui core if needed
						if ( isset( $arr[ 'ui-components' ] ) && is_array( $arr[ 'ui-components' ] ) || ( isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) ) )
						{
						//	$arr[ 'ui' ] = true; // take value true or false from headers.inc
						} //isset( $arr[ 'ui-components' ] ) && is_array( $arr[ 'ui-components' ] ) || ( isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) )
						if ( isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) && ( !in_array( 'core', $arr[ 'ui-effects' ] ) ) )
						{
							array_unshift( $arr[ 'ui-effects' ], 'core' );
						} //isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) && ( !in_array( 'core', $arr[ 'ui-effects' ] ) )
						// load the components
						if ( isset( $arr[ 'ui-theme' ] ) && file_exists( LEPTON_PATH . '/modules/lib_jquery/jquery-ui/themes/' . $arr[ 'ui-theme' ] ) )
						{
							$output .= '<link rel="stylesheet" type="text/css" href="' . LEPTON_URL . '/modules/lib_jquery/jquery-ui/themes/' . $arr[ 'ui-theme' ] . '/jquery-ui.css' . '" media="all" />' . "\n";
						} //isset( $arr[ 'ui-theme' ] ) && file_exists( LEPTON_PATH . '/modules/lib_jquery/jquery-ui/themes/' . $arr[ 'ui-theme' ] )
						if ( isset( $arr[ 'core' ] ) && $arr[ 'core' ] === true )
						{
							$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/modules/lib_jquery/jquery-core/jquery-core.min.js' . '"></script>' . "\n";
						} //isset( $arr[ 'core' ] ) && $arr[ 'core' ] === true
						if ( isset( $arr[ 'ui' ] ) && $arr[ 'ui' ] === true )
						{
							$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/modules/lib_jquery/jquery-ui/jquery-ui.min.js' . '"></script>' . "\n";
						} //isset( $arr[ 'ui' ] ) && $arr[ 'ui' ] === true
						if ( isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] ) )
						{
							foreach ( $arr[ 'ui-effects' ] as $item )
							{
								$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/modules/lib_jquery/jquery-ui/ui/jquery.effects.' . $item . '.min.js' . '"></script>' . "\n";
							} //$arr[ 'ui-effects' ] as $item
						} //isset( $arr[ 'ui-effects' ] ) && is_array( $arr[ 'ui-effects' ] )
						if ( isset( $arr[ 'ui-components' ] ) && is_array( $arr[ 'ui-components' ] ) )
						{
							foreach ( $arr[ 'ui-components' ] as $item )
							{
								$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/modules/lib_jquery/jquery-ui/ui/jquery.ui.' . $item . '.min.js' . '"></script>' . "\n";
							} //$arr[ 'ui-components' ] as $item
						} //isset( $arr[ 'ui-components' ] ) && is_array( $arr[ 'ui-components' ] )
						if ( isset( $arr[ 'all' ] ) && is_array( $arr[ 'all' ] ) )
						{
							foreach ( $arr[ 'all' ] as $item )
							{
								$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/modules/lib_jquery/plugins/' . $item . '/' . $item . '.js' . '"></script>' . "\n";
							} //$arr[ 'all' ] as $item
						} //isset( $arr[ 'all' ] ) && is_array( $arr[ 'all' ] )
						if ( isset( $arr[ 'individual' ] ) && is_array( $arr[ 'individual' ] ) )
						{
							foreach ( $arr[ 'individual' ] as $section_name => $item )
							{
								if ( $section_name == strtolower( $individual ) )
								{
									$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/modules/lib_jquery/plugins/' . $item . '/' . $item . '.js' . '"></script>' . "\n";
								} //$section_name == strtolower( $individual )
							} //$arr[ 'individual' ] as $section_name => $item
						} //isset( $arr[ 'individual' ] ) && is_array( $arr[ 'individual' ] )
						break;
					case 'js':
						if ( is_array( $arr ) )
						{
							if ( isset( $arr[ 'all' ] ) )
							{
								foreach ( $arr[ 'all' ] as $item )
								{
									$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/templates/' . DEFAULT_THEME . '/js/' . $item . '"></script>' . "\n";
								} //$arr[ 'all' ] as $item
							} //isset( $arr[ 'all' ] )
							if ( isset( $arr[ 'individual' ] ) )
							{
								foreach ( $arr[ 'individual' ] as $section_name => $item )
								{
									if ( $section_name == strtolower( $individual ) )
									{
										$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/templates/' . DEFAULT_THEME . '/js/' . $item . '"></script>' . "\n";
									} //$section_name == strtolower( $individual )
								} //$arr[ 'individual' ] as $section_name => $item
							} //isset( $arr[ 'individual' ] )
						} //is_array( $arr )
						else
						{
							$output .= '<script type="text/javascript" src="' . LEPTON_URL . '/' . $arr . '"></script>' . "\n";
						}
						break;
					default:
						trigger_error( 'Unknown header type [' . $key . ']!', E_USER_NOTICE );
						break;
				} //$key
			} //$HEADERS[ $for ][ $key ] as $i => $arr
		} //array( 'meta', 'css', 'jquery', 'js' ) as $key
		// foreach( array( 'meta', 'css', 'js' ) as $key )
		
		if ( true == $print_output )
		{
			echo $output;
			define( 'LEP_HEADERS_SENT', true );
		} //true == $print_output
		else
		{
			return $output;
		}
		
	} // end function get_page_headers()

?>