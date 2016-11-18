<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		get_active_sections
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
	 *	Get the active sections of the current page
	 *
	 *	@param	int		Current page_id
	 *	@param	str		Optional block-name
	 *	@param	bool	Backend? Default is false
	 *	@return	array	Linear array within all ids of active section
	 */
	function get_active_sections( $page_id, $block = null, $backend = false )
	{
		global $database;

		$lep_active_sections = array();

		// First get all sections for this page
		$fields = array(
			'section_id',
			'module',
			'block',
			'publ_start',
			'publ_end'
		);
		
		$sql = $database->build_mysql_query(
			'SELECT',
			TABLE_PREFIX."sections",
			$fields,
			"page_id = '" . $page_id . "' ORDER BY block, position"
		);
		
		$query_sections = $database->query($sql);

		if ($query_sections->numRows() == 0)
		{
			return NULL;
		}
		
		$now = time();

		while ($section = $query_sections->fetchRow())
		{
			// skip this section if it is out of publication-date
			if ( ($backend === false) && !(($now <= $section['publ_end'] || $section['publ_end'] == 0) && ($now >= $section['publ_start'] || $section['publ_start'] == 0)))
			{
				continue;
			}
			$lep_active_sections[$section['block']][] = $section;
		}
	
		$pages_seen[$page_id] = true;

		if ( $block )
		{
			return ( isset($lep_active_sections[$block] ) )
				? $lep_active_sections[$block]
				: NULL;
		}

		$all = array();
		foreach( $lep_active_sections as $block => $values )
		{
			foreach( $values as $value )
			{
				$all[] = $value;
			}
		}
		
		return $all;
		
	}   // end function get_active_sections()

?>