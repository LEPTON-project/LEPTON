<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          show_menu2
 * @author          Brofield, LEPTON Project
 * @copyright       2006-2010 Brofield
 * @copyright       2010-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org/sm2/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
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
    This file provides backward compatibility between show_menu2 and the
    old functions show_menu() and menu(). Note that it is highly recommended 
    for you to update your templates to use show_menu2 directly.
 */    

 
class SM2_ShowMenuFormatter
{
    var $output;
    var $itemTemplate;
    var $itemFooter;
    var $menuHeader;
    var $menuFooter;
    var $defaultClass;
    var $currentClass;
    
    function output($aString) {
        if ($this->flags & SM2_BUFFER) {
            $this->output .= $aString;
        }
        else {
            echo $aString;
        }
    }
    function initialize() { $this->output = ''; }
    function startList($aPage, $aUrl) { 
        echo $this->menuHeader;
    }
    function startItem($aPage, $aUrl, $aCurrSib, $aSibCount) { 
        // determine the class string to use
        $thisClass = $this->defaultClass;
        if ($aPage['page_id'] == PAGE_ID) {
            $thisClass = $this->currentClass;
        }
        
        // format and display this item
        $item = str_replace( 
                array(
                    '[a]','[/a]','[menu_title]','[page_title]','[url]',
                    '[target]','[class]'
                    ),
                array(
                    "<a href='$aUrl' target='".$aPage['target']."'>", '</a>',
                    $aPage['menu_title'], $aPage['page_title'], $aUrl, 
                    $aPage['target'], $thisClass
                    ),
                $this->itemTemplate);
        echo $item;
    }
    function finishItem() { 
        echo $this->itemFooter;
    }
    function finishList() { 
        echo $this->menuFooter;
    }
    function finalize() { }
    function getOutput() {
        return $this->output;
    }
}

/* can be totally removed with 2.x
function show_menu(
    $aMenu          = 1, 
    $aStartLevel    = 0, 
    $aRecurse       = -1, 
    $aCollapse      = true,
    $aItemTemplate  = '<li><span[class]>[a][menu_title][/a]</span>',
    $aItemFooter    = '</li>',
    $aMenuHeader    = '<ul>',
    $aMenuFooter    = '</ul>',
    $aDefaultClass  = ' class="menu_default"',
    $aCurrentClass  = ' class="menu_current"',
    $aParent        = 0
    )
{
    static $formatter;
    if (!isset($formatter)) {
        $formatter = new SM2_ShowMenuFormatter;
    }
    
    $formatter->itemTemplate  = $aItemTemplate;
    $formatter->itemFooter    = $aItemFooter;  
    $formatter->menuHeader    = $aMenuHeader;  
    $formatter->menuFooter    = $aMenuFooter;  
    $formatter->defaultClass  = $aDefaultClass;
    $formatter->currentClass  = $aCurrentClass;
    
    $start = SM2_ROOT + $aStartLevel;
    if ($aParent != 0) {
        $start = $aParent;
    }

    $maxLevel = 0;
    if ($aRecurse == 0) {
        return;
    }
    if ($aRecurse < 0) {
        $maxLevel = SM2_ALL;
    }
    else {
        $maxLevel = SM2_START + $aRecurse - 1;
    }
    
    $flags = $aCollapse ? SM2_TRIM : SM2_ALL;
    
    // special case for default case
    if ($aStartLevel == 0 && $aRecurse == -1 && $aCollapse) {
        $maxLevel = SM2_CURR + 1;
    }

    show_menu2($aMenu, $start, $maxLevel, $flags, $formatter);
}

function page_menu(
    $aParent = 0, 
    $menu_number = 1, 
    $item_template = '<li[class]>[a][menu_title][/a]</li>', 
    $menu_header = '<ul>', 
    $menu_footer = '</ul>', 
    $default_class = ' class="menu_default"', 
    $current_class = ' class="menu_current"', 
    $recurse = LEVEL    // page['level']
    ) 
{
    show_menu($menu_number, 0, $recurse+2, true, $item_template, '', 
        $menu_header, $menu_footer, $default_class, $current_class, $aParent);
}
*/
?>