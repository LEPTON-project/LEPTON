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

// initialise semantic radio button
$('.ui.radio.checkbox')
  .checkbox()
;

/**
 *	Aldus for L* 2.3
 *	Since we're expect the params inside $_POST we're 
 *	in the need to //modify// the form like this:
 *
 */
function submit_droplet_job( aJobName, aJobValue ) {
	var temp_form_ref = document.getElementById("lepton_droplets_interface");
	if(temp_form_ref) {
		
		var temp_element = document.createElement("input");
		
		if(temp_element) {
			temp_element.setAttribute("type", "hidden" );
			temp_element.setAttribute("name", aJobName );
			temp_element.setAttribute("value", aJobValue );
			
			temp_form_ref.appendChild( temp_element );
			temp_form_ref.submit();
		}
	}
}

function confirm_del_droplet( aMessage, aItemID) {
	if(confirm( aMessage )) {
		submit_droplet_job("del", aItemID);
	}

}

function droplets_set_action( type, name ){
	var temp_form_ref = document.getElementById("droplets_manage_backups");
	if(temp_form_ref) {
		var temp_element = document.createElement("input");
		if(temp_element) {
			temp_element.setAttribute("type", "hidden" );
			temp_element.setAttribute("name", type );
			temp_element.setAttribute("value", name );
		
			temp_form_ref.appendChild( temp_element );
			temp_form_ref.submit();
		}
	}
}