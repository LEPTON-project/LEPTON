<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
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

/*
	wbmailer class
	This class is a subclass of the PHPMailer class and replaces the mail() function of PHP
*/

// Include PHPMailer class
require_once( LEPTON_PATH . "/modules/lib_phpmailer/library.php" );

class wbmailer extends PHPMailer
{
	// new mailer class (subset of PHPMailer class)
	// setting default values 
	
	/**
	 *	Constructor of the class
	 */
	public function __construct()
	{
		// set method to send out emails
		if ( WBMAILER_ROUTINE == "smtp" AND strlen( WBMAILER_SMTP_HOST ) > 5 )
		{
			// use SMTP for all outgoing mails send
			$this->IsSMTP();
			$this->Host = WBMAILER_SMTP_HOST;
			
			// check if SMTP authentification is required
			if ( WBMAILER_SMTP_AUTH == "true" && strlen( WBMAILER_SMTP_USERNAME ) > 1 && strlen( WBMAILER_SMTP_PASSWORD ) > 1 )
			{
				// use SMTP authentification
				$this->SMTPAuth = true; // enable SMTP authentification
				$this->Username = WBMAILER_SMTP_USERNAME; // set SMTP username
				$this->Password = WBMAILER_SMTP_PASSWORD; // set SMTP password
			}
		}
		else
		{
			// use PHP mail() function for outgoing mails send by Website Baker
			$this->IsMail();
		}
		
		// set language file for PHPMailer error messages
		if ( defined( "LANGUAGE" ) ) $this->SetLanguage( strtolower( LANGUAGE ), "language" ); // english default (also used if file is missing)

		// set default charset
		$this->CharSet =  defined( 'DEFAULT_CHARSET' ) ? DEFAULT_CHARSET : 'utf-8';
		
		// set default sender name
		if ( $this->FromName == 'Root User' )
		{
			$this->FromName = isset( $_SESSION[ 'DISPLAY_NAME' ] ) 
				? $_SESSION[ 'DISPLAY_NAME' ] 
				: WBMAILER_DEFAULT_SENDERNAME
				;
		}
		
		/* 
		some mail provider (lets say mail.com) reject mails send out by foreign mail 
		relays but using the providers domain in the from mail address (e.g. myname@mail.com)
		*/
		$this->From = SERVER_EMAIL; // FROM MAIL: (server mail)
		
		// set default mail formats
		$this->IsHTML( true );
		$this->WordWrap = 80;
		$this->Timeout  = 30;
	}
}

?>