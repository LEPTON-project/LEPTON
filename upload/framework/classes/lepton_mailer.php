<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
require_once (LEPTON_PATH . "/modules/lib_phpmailer/library.php");

class LEPTON_mailer extends PHPMailer
{

	/**
	 *	@var Singleton The reference to *Singleton* instance of this class
	 */
	private static $instance;
	
	/**
	 *	Return the »internal«
	 *
	 *	@param	array	Optional params
	 */
	public static function getInstance( &$settings=array() )
	{
		if (null === static::$instance)
		{
			static::$instance = new static();
			static::$instance->__construct();
		}

		return static::$instance;
	}
	
	/**
	 *	Constructor of the class
	 */
	public function __construct()
	{
		// set method to send out emails
		if ( MAILER_ROUTINE == "smtp" AND strlen( MAILER_SMTP_HOST ) > 5 )
		{
			// use SMTP for all outgoing mails send
			$this->IsSMTP();
			$this->Host = MAILER_SMTP_HOST;
			
			// check if SMTP authentification is required
			if ( MAILER_SMTP_AUTH == "true" && strlen( MAILER_SMTP_USERNAME ) > 1 && strlen( MAILER_SMTP_PASSWORD ) > 1 )
			{
				// use SMTP authentification
				$this->SMTPAuth = true; // enable SMTP authentification
				$this->Username = MAILER_SMTP_USERNAME; // set SMTP username
				$this->Password = MAILER_SMTP_PASSWORD; // set SMTP password
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
				: MAILER_DEFAULT_SENDERNAME
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
