<?php
/**
 *
 * @category        module
 * @package         droplet
 * @author          Ruud Eisinga (Ruud) John (PCWacht)
 * @author          WebsiteBaker Project
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @link			http://www.websitebaker2.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @requirements    PHP 4.4.9 and higher
 * @version         $Id$
 * @filesource		$HeadURL$
 * @lastmodified    $Date$
 *
 */

// tool_edit.php
require_once('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
require_once(WB_PATH.'/framework/functions.php');
// create admin object depending on platform (admin tools were moved out of settings with WB 2.7)
$admin = new admin('admintools', 'admintools');
$admintool_link = ADMIN_URL .'/admintools/index.php';
$module_edit_link = ADMIN_URL .'/admintools/tool.php?tool=droplets';
$template_edit_link = ADMIN_URL .'/admintools/tool.php?tool=templateedit';


?>
<div class="container">
<h2 style="margin: 0; border-bottom: 1px solid #DDD; padding-bottom: 5px;">
	Droplets
</h2>
<div>&nbsp</div>
<span class="button back"><a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets"><?php echo $TEXT['BACK'];?></a></span>
<div>&nbsp</div>
<?php

$temp_dir = WB_PATH.'/temp/droplets/';
$temp_file = '/modules/droplets/export/backup-droplets.zip';
// make the temporary working directory
mkdir($temp_dir);
$query_droplets = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_droplets ORDER BY modified_when DESC");
while($droplet = $query_droplets->fetchRow()) {
	echo 'Saving: '.$droplet["name"].'.php<br />';
	$sFile = $temp_dir.$droplet["name"].'.php';
	$fh = fopen($sFile, 'w') ;
	fwrite($fh, '//:'.$droplet['description']."\n");
	fwrite($fh, '//:'.str_replace("\n"," ",$droplet['comments'])."\n");
	fwrite($fh, $droplet['code']);
	fclose($fh);
}
echo '<br />Create archive: backup-droplets.zip<br />';

require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');
$archive = new PclZip(WB_PATH.$temp_file);
$file_list = $archive->create($temp_dir, PCLZIP_OPT_REMOVE_ALL_PATH);
if ($file_list == 0){
	echo "Packaging error: '.$archive->errorInfo(true).'";
	die("Error : ".$archive->errorInfo(true));
}
else {
	echo '<br />Backup created: <a href="'.WB_URL.$temp_file.'">Download</a>';
}

delete_directory ( $temp_dir );

?>
<div>&nbsp</div>
<span class="button back"><a href="<?php echo ADMIN_URL; ?>/admintools/tool.php?tool=droplets"><?php echo $TEXT['BACK'];?></a></span>
</div>
<?php


$admin->print_footer();


function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);          
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
?>