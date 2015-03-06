/**
 * Admin tool: Addon File Editor
 *
 * This tool allows you to "edit", "delete", "create", "upload" or "backup" files of installed 
 * Add-ons such as modules, templates and languages via LEPTON backend. This enables
 * you to perform small modifications on installed Add-ons without downloading the files first.
 *
 * 
 * @author		Christian Sommer (doc), Bianka Martinovic (BlackBird), Dietrich Roland Pehlke (aldus), LEPTON Project
 * @copyright	2008-2012 Christian Sommer (doc), Bianka Martinovic (BlackBird), Dietrich Roland Pehlke (aldus)
 * @copyright	2010-2015 LEPTON Project
 * @license     GNU General Public License
 * @version		see info.php
 * @platform	see info.php
 *
*/


// functions for the jQuery framework
$(document).ready(function() {
	// only execute on overview page
	if ($('#trigger_modules,#trigger_templates,#trigger_languages').html() == null) return;
	
	// remove navigation boxes except the very first one (#modules)
	$('.box:not("#modules")').hide();
	
	// remove links from first navigation box except the first link (Reload)
	$('#modules a:not(:first)').hide();

	// set default or user defined view mode settings
	setViewMode('modules');
	setViewMode('templates');
	setViewMode('languages');

	$('#trigger_modules, #trigger_templates, #trigger_languages')
		.toggle(
			function() {
				var id = $(this).attr('id').replace(/trigger_/, '');
				document.cookie = 'AFE_' + id + '=1';
				setViewMode(id);
			},
			
			function() {
				var id = $(this).attr('id').replace(/trigger_/, '');
				document.cookie = 'AFE_' + id + '=0';
				setViewMode(id);
			}
		);

});

function setViewMode(id) {
	// get class (expanded/collapsed)
	var trigger = $('#trigger_' + id);
	var view = $('#toggle_' + id)

	// initialize trigger
	if (trigger.attr('class') == '' && document.cookie.indexOf('AFE_' + id) == -1) {
		// initialize triggers (set collapsed)
		trigger.addClass('expanded');
		view.addClass('hidden');
		document.cookie = 'AFE_' + id + '=0';

	} else if (document.cookie.indexOf('AFE_' + id + '=1') > -1) {
		// toggle from collapsed to expanded
		trigger.removeClass('expanded').addClass('collapsed');
		view.removeClass('hidden');
		
	} else {
		// toggle from expanded to collapsed
		trigger.removeClass('collapsed').addClass('expanded');
		view.addClass('hidden');
	}
}