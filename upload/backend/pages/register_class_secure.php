<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

 // needed for ajax call in users directory
$files_to_register = array(
	"add.php",
	"delete.php",
	"empty_trash.php",
	"index.php",
	"master_index.php",
	"modify.php",
	"move_down.php",
	"move_up.php",
	"overview.php",
	"restore.php",
	"save.php",
	"search.php",
	"search_pagetree.php",
	"sections.php",
	"sections_save.php",
	"settings.php",
	"settings2.php",
	"trash.php",
	"update_page_tree.php",
	"update_page_sections.php"
);

LEPTON_secure::getInstance()->accessFiles( $files_to_register );

?>