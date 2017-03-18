<?php

/**
 *	@template       Lepton-Start
 *	@version        see info.php of this template
 *	@author         cms-lab
 *	@copyright		2010-2017 CMS-LAB
 *	@license        http://creativecommons.org/licenses/by/3.0/
 *	@license terms  see info.php of this template
 *	@platform       see info.php of this template
 */

// simple exit as it doesn't make sense to go on
if ( !defined( 'LEPTON_PATH' ) ) exit;

// TEMPLATE CODE STARTS BELOW
?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET; ?>" />
	<title><?php page_title(); ?></title>  
	<meta name="description" content="<?php page_description(); ?>" />
	<meta name="keywords" content="<?php page_keywords(); ?>" />
	<?php get_page_headers();	?>  
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/template.css" media="screen,projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/print.css" media="print" />
</head>
<body>
<div id="site">
	<div id="head">
		<a href="<?php echo LEPTON_URL;?>/"><img class="head_img" src="<?php echo TEMPLATE_DIR;?>/img/1.jpg" width="900" height="180" alt="Head" /></a>
	</div>
	<div id="headtitle">
		<?php page_header(); ?>
	</div>

	<!-- Left Column -->
	<div id="side">	
		<div id="navi1">
		<!-- Main-Navigation on the left) -->
    	<?php
			show_menu2(1, SM2_ROOT, SM2_ROOT+2, SM2_TRIM|SM2_PRETTY|SM2_XHTML_STRICT); 
    	?>
		</div>
		
		<!-- OPTIONAL: display frontend search -->
		[[LEPTON_SearchBox]]
		<!-- END frontend search -->

		<!-- OPTIONAL: display frontend login -->
		<div id="login">
		[[LoginBox]]
		</div>

		<div id="frontedit">
			[[editthispage]]
		</div>

	</div>    <!-- END left column -->   

	<!-- Content -->
	
	<div id="cont">
		<?php page_content(1); ?>	
	</div>
		<br style="clear: both;" />
	<div id="foot">
	<?php 
		show_menu2(2, SM2_ROOT, SM2_ALL, SM2_TRIM|SM2_PRETTY|SM2_XHTML_STRICT);
	?>
</div>

<!-- Block Bottom -->
	<div id="basic">
		<div id="links">
			<?php page_footer(); ?>
		</div>
		<div id="design">
			<a href='http://cms-lab.com'>Design by CMS-LAB</a>
		</div>
	</div>
</div>
<?php
	get_page_footers();
?>
</body>
</html>
