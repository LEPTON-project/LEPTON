/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          Droplets
 * @author          LEPTON Project
 * @copyright       2010-2015 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */
 
if ( typeof jQuery != 'undefined' ) {
	try {
		jQuery("a[rel=fancybox]").fancybox({'width':'70%','height':'70%'});
	}
	catch (x) {}
	
	// check / uncheck all checkboxes
	jQuery('[type="checkbox"]#checkall').click( function() {
    	jQuery("input[@name=markeddroplet\[\]][type='checkbox']").attr('checked', jQuery(this).is(':checked'));
	});
}

