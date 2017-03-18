<?php

/**
 *  @template       LEPTON-Start
 *  @version        see info.php of this template
 *  @author         cms-lab
 * @copyright       2010-2017 CMS-LAB
 *  @license        http://creativecommons.org/licenses/by/3.0/
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
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

// OBLIGATORY VARIABLES
$template_directory     = 'lepton2';
$template_name          = 'lepton2-start';
$template_function      = 'template';
$template_version       = '2.3.0';
$template_platform      = '2.0';
$template_author        = 'CMS-LAB';
$template_license       = 'http://creativecommons.org/licenses/by/3.0/';
$template_license_terms = 'you have to keep the frontend-backlink to cms-lab untouched';
$template_description   = 'This template is simply a start';
$template_guid          = '1bd949a2-46e6-40e3-a3fd-a372a9ede147';

// OPTIONAL VARIABLES FOR ADDITIONAL MENUES AND BLOCKS
$menu[ 1 ]  = 'Main';
$menu[ 2 ]  = 'Foot';
$menu[ 3 ]  = 'Pseudomenu';
$block[ 1 ] = 'Content';
$block[ 2 ] = 'News';

?>