<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_content
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
}
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	}
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	}
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

	function page_content( $block = 1 )
	{
		// Get outside objects
		global $TEXT, $MENU, $HEADING, $MESSAGE;
		global $globals;
		global $database;
		global $wb;
		$admin =& $wb;
		if ( $wb->page_access_denied == true )
		{
			echo $MESSAGE[ 'FRONTEND_SORRY_NO_VIEWING_PERMISSIONS' ];
			return;
		}
		if ( $wb->page_no_active_sections == true )
		{
			echo $MESSAGE[ 'FRONTEND_SORRY_NO_ACTIVE_SECTIONS' ];
			return;
		}
		if ( isset( $globals ) AND is_array( $globals ) )
		{
			foreach ( $globals AS $global_name )
			{
				global $$global_name;
			}
		}
		// Make sure block is numeric
		if ( !is_numeric( $block ) )
		{
			$block = 1;
		}
		// Include page content
		if ( !defined( 'PAGE_CONTENT' ) OR $block != 1 )
		{
			$page_id               = intval( $wb->page_id );
			// set session variable to save page_id only if PAGE_CONTENT is empty
			$_SESSION[ 'PAGE_ID' ] = !isset( $_SESSION[ 'PAGE_ID' ] ) ? $page_id : $_SESSION[ 'PAGE_ID' ];
			// set to new value if page_id changed and not 0
			if ( ( $page_id != 0 ) && ( $_SESSION[ 'PAGE_ID' ] <> $page_id ) )
			{
				$_SESSION[ 'PAGE_ID' ] = $page_id;
			}
			
			// First get all sections for this page
			$query_sections = $database->query( "SELECT section_id,module,publ_start,publ_end FROM " . TABLE_PREFIX . "sections WHERE page_id = '" . $page_id . "' AND block = '$block' ORDER BY position" );
			// If none were found, check if default content is supposed to be shown
			if ( $query_sections->numRows() == 0 )
			{
				if ( $wb->default_block_content == 'none' )
				{
					return;
				}
				if ( is_numeric( $wb->default_block_content ) )
				{
					$page_id = $wb->default_block_content;
				}
				else
				{
					$page_id = $wb->default_page_id;
				}
				$query_sections = $database->query( "SELECT section_id,module,publ_start,publ_end FROM " . TABLE_PREFIX . "sections WHERE page_id = '" . $page_id . "' AND block = '$block' ORDER BY position" );
				// Still no cotent found? Give it up, there's just nothing to show!
				if ( $query_sections->numRows() == 0 )
				{
					return;
				}
			}
			// Loop through them and include their module file
			while ( $section = $query_sections->fetchRow() )
			{
				// skip this section if it is out of publication-date
				$now = time();
				if ( !( ( $now <= $section[ 'publ_end' ] || $section[ 'publ_end' ] == 0 ) && ( $now >= $section[ 'publ_start' ] || $section[ 'publ_start' ] == 0 ) ) )
				{
					continue;
				}
				$section_id = $section[ 'section_id' ];
				$module     = $section[ 'module' ];
				// make a anchor for every section.
				if ( defined( 'SEC_ANCHOR' ) && SEC_ANCHOR != '' )
				{
					echo '<a class="section_anchor" id="' . SEC_ANCHOR . $section_id . '"></a>';
				}
				// check if module exists - feature: write in errorlog
				if ( file_exists( LEPTON_PATH . '/modules/' . $module . '/view.php' ) )
				{
					// fetch content -- this is where to place possible output-filters (before highlighting)
					ob_start(); // fetch original content
					require( LEPTON_PATH . '/modules/' . $module . '/view.php' );
					$content = ob_get_clean();
				}
				else
				{
					continue;
				}
				
				// highlights searchresults
				if ( isset( $_GET[ 'searchresult' ] ) && is_numeric( $_GET[ 'searchresult' ] ) && !isset( $_GET[ 'nohighlight' ] ) && isset( $_GET[ 'sstring' ] ) && !empty( $_GET[ 'sstring' ] ) )
				{
					$arr_string = explode( " ", $_GET[ 'sstring' ] );
					if ( $_GET[ 'searchresult' ] == 2 ) // exact match
					{
						$arr_string[ 0 ] = str_replace( "_", " ", $arr_string[ 0 ] );
					}
					echo search_highlight( $content, $arr_string );
				}
				else
				{
					echo $content;
				}
			}
		}
		else
		{
			require( PAGE_CONTENT );
		}
	}


?>