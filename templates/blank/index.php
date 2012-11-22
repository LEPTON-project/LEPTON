<?php

/**
 *  @template       blank
 *  @version        see info.php of this template
 *  @author         erpe
 *  @copyright      2010-2011 erpe
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php



// TEMPLATE CODE STARTS BELOW
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo defined('DEFAULT_CHARSET') ? DEFAULT_CHARSET : 'utf-8'; ?>" />
	<meta name="description" content="<?php page_description(); ?>" />
	<meta name="keywords" content="<?php page_keywords(); ?>" />
<?php 
	/**
	 *	Automatically include optional WB module files (frontend.css, frontend.js)
	 *	Try to use the new build in method of the wb-object.
	 */
	if ( method_exists( $wb, "register_frontend_modfiles" ) ) {
	
		echo $wb->register_frontend_modfiles();
	
	}
?>
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/template.css" media="screen,projection" />
	<title><?php page_title('', '[WEBSITE_TITLE]'); ?></title>
</head>
<body>
<?php
	/**
	 *	TEMPLATE CODE STARTS BELOW
	 *	output only the page content, nothing else
	 */
	page_content();
?>  
</body>
</html>