<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Bianka (WebBird)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			      http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 *
 */

//defined('WB_PATH') OR die(header('Location: ../index.php'));

global $TEXT;

require_once('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');
require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
require_once( dirname(__FILE__).'/functions.inc.php' );

// Load Language file
if(LANGUAGE_LOADED) {
	if(!file_exists(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php')) {
		require_once(WB_PATH.'/modules/droplets/languages/EN.php');
	} else {
		require_once(WB_PATH.'/modules/droplets/languages/'.LANGUAGE.'.php');
	}
}

// create admin object depending on platform (admin tools were moved out of settings with WB 2.7)
$admin = new admin('admintools', 'admintools');
$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';
$template_edit_link = ADMIN_URL .'/admintools/tool.php?tool=templateedit';

// And... action
$admintool_url = ADMIN_URL .'/admintools/index.php';

// file to delete?
if ( isset( $_GET['del'] ) ) {
    if ( $_GET['del'] !== 'all' ) {
        if ( file_exists( WB_PATH.'/modules/droplets/export/'.$_GET['del'] ) ) {
            unlink( WB_PATH.'/modules/droplets/export/'.$_GET['del'] );
        }
    }
    else {
        $backup_files = wb_find_backups( WB_PATH.'/modules/droplets/export/' );
        foreach ( $backup_files as $file ) {
            unlink( WB_PATH.'/modules/droplets/export/'.$file );
            echo header( "Location: ".ADMIN_URL."/admintools/tool.php?tool=droplets\n\n" );
        }
    }
}

$backup_files = wb_find_backups( WB_PATH.'/modules/droplets/export/' );

// no more files
if ( ! count( $backup_files ) > 0 ) {
    echo header( "Location: ".ADMIN_URL."/admintools/tool.php?tool=droplets\n\n" );
}

?>
<link media="screen" type="text/css" rel="stylesheet" href="<?php echo WB_URL; ?>/modules/droplets/backend.css" />
<div class="container">
<h2>Droplets</h2>

<span class="button back" style="float:left;"><a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets"><?php echo $TEXT['BACK'];?></a></span>
<button class="warn" style="float: right;" type="button" name="backup_mgmt"
        onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']?>', '<?php echo WB_URL; ?>/modules/droplets/manage_backups.php?del=all');">
        <?php echo $DR_TEXT['DELETE_ALL']; ?></button><br style="clear: right" />
<br />

<?php
// include jQuery TableSorter Plugin if available
    if ( file_exists( WB_PATH.'/modules/jqueryadmin' ) && file_exists( WB_PATH.'/modules/jqueryadmin/plugins/tablesorter' ) ) {
        include_once( WB_PATH.'/modules/jqueryadmin/include.php' );
        echo jQueryAdmin_backendPreset( 'droplets' );
    }
    
if ( count( $backup_files ) > 0 ) {
    // sort by name
    sort($backup_files);
    echo '<table class="droplets" style="width: 100%;" id="myTable">',
         "<thead><tr class=\"row_b\"><th align=\"left\">",$TEXT['NAME'],"</th><th align=\"right\">",$TEXT['SIZE']," (Byte)</th><th align=\"left\">",$TEXT['DATE'],"</th><th align=\"right\">",$TEXT['FILES'],"</th><th align=\"left\"></th></tr></thead><tbody>";
	$row = 'a';
	foreach ( $backup_files as $file ) {
        // stat
        $stat  = stat(WB_PATH.'/modules/droplets/export/'.$file);
        // get zip contents
        $zip   = new PclZip(WB_PATH.'/modules/droplets/export/'.$file);
        $count = $zip->listContent();
        echo '<tr class="row_'.$row.'">',
             '<td><a class="tooltip" href="#"><img src="'.WB_URL.'/modules/droplets/img/list.png" alt="Content" />',
             '<span>'.implode( "<br />", array_map( create_function('$cnt', 'return $cnt["filename"];'), $count ) ).'</span> '.$file.'</a>',
             '</td>',
             "<td align=\"right\">", $stat['size'], "</td>",
             "<td>", strftime( '%c', $stat['ctime'] ), "</td>",
             "<td align=\"right\">", count($count), "</td>",
             "<td><a href=\"".WB_URL."/modules/droplets/export/$file\" title=\"Download\"><img src=\"".WB_URL."/modules/droplets/img/download.png\" alt=\"Download\" /></a>",
             "    <a href=\"javascript: confirm_link('".$TEXT['ARE_YOU_SURE']."', '".ADMIN_URL."/admintools/tool.php?tool=droplets&amp;recover=$file');\" title=\"Recover\"><img src=\"".WB_URL."/modules/droplets/img/import.png\" alt=\"Recover\" /></a>",
             "    <a href=\"javascript: confirm_link('".$TEXT['ARE_YOU_SURE']."', '".WB_URL."/modules/droplets/manage_backups.php?del=$file');\" title=\"Delete\"><img src=\"".THEME_URL."/images/delete_16.png\" alt=\"X\" /></a></td></tr>\n";
  // Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
		}
   
    echo "</tbody></table>\n";
}
?>
<div>&nbsp;</div>
<span class="button back"><a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets"> <?php echo $TEXT['BACK'];?></a></span>
<?php

// Print admin footer
$admin->print_footer();

?>
