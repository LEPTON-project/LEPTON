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

//initialize twig template engine
	global $parser;		// twig parser
	global $loader;		// twig file manager
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );


/**
 *
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;

$redirect_url = (isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : "");



unset($_SESSION['result_message']);

		$data = array(
	'TEXT_FORGOT'		=>	$MENU['FORGOT'], 
	'MESSAGE_COLOR'		=>	$message_color,
	'MESSAGE'		    =>	$message,
	'FORGOT_URL'		=>	FORGOT_URL,  
	'URL'			    =>	$redirect_url,
	'TEXT_EMAIL'		=>	$TEXT['EMAIL'],
	'TEXT_SEND_DETAILS'	=>	$TEXT['SEND_DETAILS'],
	'TEXT_LOGOUT'		=>	$MENU['LOGOUT'],
	'TEXT_RESET'		=>	$TEXT['RESET'],
	'HASH'				=>	$hash,
	'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS']
		);
		
		echo $parser->render( 
			"forgot_form.lte",	//	template-filename
			$data			//	template-data
		);

?>