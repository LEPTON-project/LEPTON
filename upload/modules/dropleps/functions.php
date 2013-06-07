<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH'))
{
    include(LEPTON_PATH . '/framework/class.secure.php');
}
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while (($level < 10) && (!file_exists($root . '/framework/class.secure.php')))
    {
        $root .= $oneback;
        $level += 1;
    }
    if (file_exists($root . '/framework/class.secure.php'))
    {
        include($root . '/framework/class.secure.php');
    }
    else
    {
        trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
    }
}
// end include class.secure.php

/**
 * this function may be called by modules to handle a droplep upload
 **/
function dropleps_upload( $input ) {

    global $database, $admin;
    
    // Set temp vars
    $temp_dir   = LEPTON_PATH.'/temp/';
    $temp_file  = $temp_dir . $_FILES[$input]['name'];
    $temp_unzip = LEPTON_PATH.'/temp/unzip/';
    $errors     = array();

    // Try to upload the file to the temp dir
    if( ! move_uploaded_file( $_FILES[$input]['tmp_name'], $temp_file ) )
    {
   	    return array( 'error', $admin->lang->translate( 'Upload failed' ) );
    }

    $result = dropleps_import( $temp_file, $temp_unzip );

    // Delete the temp zip file
    if( file_exists( $temp_file) )
    {
        unlink( $temp_file );
    }
    rm_full_dir($temp_unzip);

    // show errors
    if ( isset( $result['errors'] ) && is_array( $result['errors'] ) && count( $result['errors'] ) > 0 ) {
        return array( 'error', $result['errors'], NULL );
    }
    
    // return success
    return array( 'success', $result['count'] );
    
}   // end function dropleps_upload()


/**
 * this function may be called by modules to install a droplep 
 **/
function droplep_import( $temp_file, $temp_unzip ) {

    global $admin, $database;

    // Include the PclZip class file
    if (!function_exists("PclZipUtilPathReduction")) {
    require_once(LEPTON_PATH.'/modules/lib_lepton/pclzip/pclzip.lib.php');
    }
    $errors  = array();
    $count   = 0;
    $archive = new PclZip($temp_file);
    $list    = $archive->extract(PCLZIP_OPT_PATH, $temp_unzip);

    // now, open all *.php files and search for the header;
    // an exported droplet starts with "//:"
    if ( $dh = opendir($temp_unzip) ) {
        while ( false !== ( $file = readdir($dh) ) )
        {
            if ( $file != "." && $file != ".." )
            {
                if ( preg_match( '/^(.*)\.php$/i', $file, $name_match ) ) {
                    // Name of the Droplet = Filename
                    $name  = $name_match[1];
                    // Slurp file contents
                    $lines = file( $temp_unzip.'/'.$file );
                    // First line: Description
                    if ( preg_match( '#^//\:(.*)$#', $lines[0], $match ) ) {
                        $description = $match[1];
                    }
                    // Second line: Usage instructions
                    if ( preg_match( '#^//\:(.*)$#', $lines[1], $match ) ) {
                        $usage       = addslashes( $match[1] );
                    }
                    // Remaining: Droplet code
                    $code = implode( '', array_slice( $lines, 2 ) );
                    // replace 'evil' chars in code
                    $tags = array('<?php', '?>' , '<?');
                    $code = addslashes(str_replace($tags, '', $code));
                    // Already in the DB?
                    $stmt  = 'INSERT';
                    $id    = NULL;
                    $found = $database->get_one("SELECT * FROM ".TABLE_PREFIX."mod_dropleps WHERE name='$name'");
                    if ( $found && $found > 0 ) {
                        $stmt = 'REPLACE';
                        $id   = $found;
                    }
                    // execute
                    $result = $database->query("$stmt INTO ".TABLE_PREFIX."mod_dropleps VALUES('$id','$name','$code','$description','".time()."','".$admin->get_user_id()."',1,0,0,0,'$usage')");
                    if( ! $database->is_error() ) {
                        $count++;
                        $imports[$name] = 1;
                    }
                    else {
                        $errors[$name] = $database->get_error();
                    }
                    // try to remove the temp file
                    unlink( $temp_unzip.'/'.$file);
                }
            }
        }
        closedir($dh);
    }
    
    return array( 'count' => $count, 'errors' => $errors, 'imported'=> $imports );
    
}   // end function droplep_import()

?>