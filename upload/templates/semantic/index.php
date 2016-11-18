<?php

/**
 *  @template       Lepton-Start
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2017 CMS-LAB
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



// TEMPLATE CODE STARTS BELOW
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET; ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<title><?php page_title(); ?></title>  
	<meta name="description" content="<?php page_description(); ?>" />
	<meta name="keywords" content="<?php page_keywords(); ?>" />
	
	<script type='text/javascript' src='<?php echo LEPTON_URL; ?>/modules/lib_jquery/jquery-core/jquery-core.min.js' ></script>
	<script type='text/javascript' src='<?php echo LEPTON_URL; ?>/modules/lib_jquery/jquery-core/jquery-migrate.min.js' ></script>
	<script type='text/javascript' src='<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.js' ></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo LEPTON_URL; ?>/modules/lib_semantic/dist/semantic.min.css" media="screen,projection" />	
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/template.css" media="screen,projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_DIR; ?>/css/print.css" media="print" />
	
	<?php get_page_headers();	?>
	<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
	<script type="text/javascript">window.cookieconsent_options = {'message':'This website uses cookies. When you browse on this site, you agree to the use of cookies.','dismiss':'agree!','learnMore':'More info','link':null,'theme':'dark-bottom'};</script>
	<script type='text/javascript' src='<?php echo TEMPLATE_DIR; ?>/js/cookieconsent.js' ></script>
	<!-- End Cookie Consent plugin -->	
  <script>
  $(document)
    .ready(function() {

      // fix menu when passed
      $('.masthead')
        .visibility({
          once: false,
          onBottomPassed: function() {
            $('.fixed.menu').transition('fade in');
          },
          onBottomPassedReverse: function() {
            $('.fixed.menu').transition('fade out');
          }
        })
      ;

      // create sidebar and attach to menu open
      $('.ui.sidebar')
        .sidebar('attach events', '.toc.item')
      ;

    })
  ;
  </script>
  
</head>
<body>

<!-- Following Menu -->
<div class="ui large top fixed hidden menu">
  <div class="ui container">
	<?php 
    show_menu2(
        $aMenu         = 1,
		$aStart        = SM2_ROOT,
	   $aMaxLevel      = SM2_START,
	   $aOptions       = SM2_ALL|SM2_PRETTY,
	   $aItemOpen      = '[li]<a href="[url]" target="[target]" class="item">[menu_title]</a>',
	   $aItemClose     = '</li>',
       $aMenuOpen      = '[ul]',
       $aMenuClose     = '</ul>',
       $aTopItemOpen   = false,
       $aTopMenuOpen   = false
        );
  ?>
  </div>
</div>

<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu">
	<?php 
    show_menu2(
        $aMenu         = 1,
		$aStart        = SM2_ROOT,
	   $aMaxLevel      = SM2_START,
	   $aOptions       = SM2_ALL|SM2_PRETTY,
	   $aItemOpen      = '[li]<a href="[url]" target="[target]" class="item">[menu_title]</a>',
	   $aItemClose     = '</li>',
       $aMenuOpen      = '[ul]',
       $aMenuClose     = '</ul>',
       $aTopItemOpen   = false,
       $aTopMenuOpen   = false
        );
  ?>
</div>


<!-- Page Contents -->
<div class="pusher">
  <div class="ui inverted vertical masthead center aligned segment">

    <div class="ui container">
      <div class="ui large secondary inverted pointing menu">
        <a class="toc item">
          <i class="sidebar icon"></i>
        </a>
	<?php 
    show_menu2(
        $aMenu         = 1,
		$aStart        = SM2_ROOT,
	   $aMaxLevel      = SM2_START,
	   $aOptions       = SM2_ALL|SM2_PRETTY,
	   $aItemOpen      = '[li]<a href="[url]" target="[target]" class="item">[menu_title]</a>',
	   $aItemClose     = '</li>',
       $aMenuOpen      = '[ul]',
       $aMenuClose     = '</ul>',
       $aTopItemOpen   = false,
       $aTopMenuOpen   = false
        );
  ?>
      </div>
    </div>

    <div class="ui text container">
      <h1 class="ui green header">
        LEPTON CMS
      </h1>
      <h2>feel free to keep it strictly simple...</h2>
      <div class="ui huge primary positive button"><a href="http://lepton-cms.org" target="_blank">Get LEPTON <i class="right arrow icon"></i></a></div>
    </div>

  </div>

  <div class="ui vertical stripe segment">
    <div class="ui middle aligned stackable grid container">
      <div class="row">
        <div class="eight wide column">
          <h3 class="ui header">LEPTON is easy-to-use </h3>
          <p>LEPTON is easily installed and started, and - what is really important - can easily be adapted to fit the needs of nearly all web appearences.</p>
          <h3 class="ui header">LEPTON is full customizable </h3>
          <p>LEPTON needs a MySQL database, the most common database on webspaces..</p>
        </div>
        <div class="six wide right floated column">
          <img src="<?php echo TEMPLATE_DIR; ?>/img/white-image.png" class="ui large bordered rounded image">
        </div>
      </div>
      <div class="row">
        <div class="center aligned column">
          <a class="ui huge button" href="http://lepton-cms.org" target="_blank">Check It Out</a>
        </div>
      </div>
    </div>
  </div>


  <div class="ui vertical stripe quote segment">
    <div class="ui equal width stackable internally celled grid">
      <div class="center aligned row">
        <div class="column">
          <h3>"What a Site"</h3>
          <p>That is what they all say about your site</p>
        </div>
        <div class="column">
          <h3>"I shouldn't have gone with a competitor."</h3>
          <p>
            <img src="<?php echo TEMPLATE_DIR; ?>/img/nan.jpg" class="ui avatar image"> <b>Nan</b> Chief Competitor Officer
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="ui vertical stripe segment">
    <div class="ui text container">
      <h3 class="ui header">This is your content!</h3>
		<?php page_content(1); ?>
    </div>
  </div>


  <div class="ui inverted vertical footer segment">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="three wide column">
          <h4 class="ui inverted header">About</h4>
          <div class="ui inverted link list">
            <a href="#" class="item">Sitemap</a>
            <a href="#" class="item">Contact Us</a>
            <a href="#" class="item">Homepage</a>
            <a href="#" class="item">Customize</a>
          </div>
        </div>
        <div class="three wide column">
          <h4 class="ui inverted header">LEPTON Services</h4>
          <div class="ui inverted link list">
            <a href="http://doc.lepton-cms.org" target="_blank" class="item">Documentation</a>
            <a href="http://lepton-cms.com" target="_blank"  class="item">Addons</a>
            <a href="http://forum.lepton-cms.org/" target="_blank"  class="item">Forum</a>
            <a href="http://semantic-ui.com" target="_blank" class="item">Semantic UI</a>
          </div>
        </div>
        <div class="seven wide column">
          <h4 class="ui inverted header">My Information</h4>
          <p><?php page_footer(); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php get_page_footers(); ?>
</body>
</html>