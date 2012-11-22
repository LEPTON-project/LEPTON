/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the BSD License.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          jsadmin 
 * @author          WebsiteBaker Project
 * @author          LEPTON Project
 * @copyright       2004-2010, Ryan Djurovich,WebsiteBaker Project
 * @copyright       2010-2011, LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         BSD License
 * @license_terms   please see info.php of this module
 * @version         $Id: restore_pages.js 688 2011-06-30 21:56:15Z erpe $
 *
 */ 


// Copyright 2006 Stepan Riha
// www.nonplus.net

// Array of ids that can be toggled using toggle_visibility()
JsAdmin.toggled_ids = function() {
	var links = document.getElementsByTagName('a');
	var ids = [];
	var reId = /toggle_visibility\s*\(\s*\'([^\']+)/;

	for(var i = 0; i < links.length; i++) {
		var href = links[i].href || '';
		var match = href.match(reId);
		if(!match) {
			continue;
		}
		var id = match[1];
		ids.push(id);
	}

	if(ids.length > 0) {
		return ids;
	} else {
		return false;
	}
};

// Expand sections stored in the wb_jsadmin_pages cookie
JsAdmin.restore_toggled = function() {
	var ids = this.toggled_ids();

	if(!ids) {
		return;
	}

	var saved = this.util.readCookie('wb_jsadmin_pages');
	if(!saved) {
		return;
	}

	var reNum = /(\d+)/;

	saved = ',' + saved + ',';

	for(var i = ids.length-1; i >= 0; i--) {
		var id = ids[i];
		if(saved.indexOf(',' + id + ',') >= 0) {
			toggle_visibility(id);
			var match = id.match(reNum);
			if(match)
				toggle_plus_minus(match[1]);
		}
	}
};

// Store expanded section ids in the wb_jsadmin_pages cookie
JsAdmin.save_toggled = function() {

	var ids = this.toggled_ids();

	if(!ids) {
		return;
	}

	var visible = [];
	for(var i = 0; i < ids.length; i++) {
		var id = ids[i];
		var elt = YAHOO.util.Dom.get(id);
		var display = elt.style.display;
		if(display == 'block') {
			visible.push(ids[i]);
		}
	}

	var ids = visible.join(",");
	this.util.createCookie('wb_jsadmin_pages', ids, 14);
};

