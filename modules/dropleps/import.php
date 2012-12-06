<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          dropleps
 * @author          LEPTON Project
 * @copyright       2010-2012, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

 // include class.secure.php to protect this file and the whole CMS!
if ( defined( 'WB_PATH' ) )
{
    include( WB_PATH . '/framework/class.secure.php' );
}
else
{
    $root  = "../";
    $level = 1;
    while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
    {
        $root .= "../";
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



function wb_unpack_and_import( $temp_file, $temp_unzip ) {

    global $admin, $database;

    // Include the PclZip class file
    if (!function_exists("PclZipUtilPathReduction")) {
    require_once(WB_PATH.'/modules/lib_lepton/pclzip/pclzip.lib.php');
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
    
}   // end function wb_unpack_and_import()


?>