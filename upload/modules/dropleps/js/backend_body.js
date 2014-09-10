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
