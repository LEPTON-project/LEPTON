<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		page_tree
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

if ( !defined( 'LEPTON_PATH' ) ) die();

/**
 *	Generates a page-tree (array) by given parameters (see below).
 *
 *	@param	int		Any root-(page) id. Default = 0.
 *	@param	array	Storage-Array for the results. Pass by reference!
 *	@param	array	A linear list of field-names to collect. As default
 *					'page_id', 'page_title', 'menu_title', 'parent','position','visibility' are
 *					collected in the result-array.
 *					Keep in mind that also 'subpages' is generated!
 *
 *	@return	nothing	As the storage is passed by reference.
 *
 *	@notice:	This function is not included in the summary.functions.php or in any other summary.
 *
 */
function page_tree( $root_id=0, &$page_storage, $fields=array('page_id', 'page_title', 'menu_title', 'parent','position','visibility') ) {
	global $database, $LEPTON_CORE_all_pages;
	
	if (!in_array('page_id', $fields)) $fields[] ="page_id";
	if (!in_array('parent', $fields)) $fields[] = "parent";
	if (!in_array('visibility', $fields)) $fields[] = "visibility";
	
	$select_fields = "`".implode("`,`", $fields)."`";
	
 	$LEPTON_CORE_all_pages = array();
	
	$database->execute_query(
		"SELECT ".$select_fields." FROM `".TABLE_PREFIX."pages` ORDER BY `parent`,`position`",
		true,
		$LEPTON_CORE_all_pages
	);
	
	LEPTON_CORE_make_list( $root_id, $page_storage );
 }
 
/**
 *	Internal Sub-function for "page_tree" to build the page-tree via recursive calls.
 *
 *	@param	int		Root-Id
 *	@param	array	Result-Storage. Pass by reference!
 *
 */ 
function LEPTON_CORE_make_list( $aNum, &$aRefArray ) {
	global $LEPTON_CORE_all_pages, $TEXT;
	
	foreach($LEPTON_CORE_all_pages as &$ref) {
		
		if ($ref['parent'] > $aNum) break;
		
		if ($ref['parent'] == $aNum) {
			
			switch( $ref['visibility'] ) {
			
				case 'public':
					$ref['status_icon'] = "visible_16.png";
					$ref['status_text'] = $TEXT['PUBLIC'];
					break;
			
				case 'private':
					$ref['status_icon'] = "private_16.png";
					$ref['status_text'] = $TEXT['PRIVATE'];
					break;
			
				case 'registered':
					$ref['status_icon'] = "keys_16.png";
					$ref['status_text'] = $TEXT['REGISTERED'];
					break;
				
				case 'hidden':
					$ref['status_icon'] = "hidden_16.png";
					$ref['status_text'] = $TEXT['HIDDEN'];
					break;
				
				case 'none':
					$ref['status_icon'] = "none_16.png";
					$ref['status_text'] = $TEXT['NONE'];
					break;
				
				case 'deleted':
					$ref['status_icon'] = "deleted_16.png";
					$ref['status_text'] = $TEXT['DELETED'];
					break;

			}

			$ref['subpages'] = array();
			LEPTON_CORE_make_list( $ref['page_id'], $ref['subpages'] );
						
			if (isset($ref['link'])) {
				$ref['link'] = PAGES_DIRECTORY.$ref['link'].PAGE_EXTENSION;
			}
			
			$aRefArray[] = &$ref;
		}
	}
}