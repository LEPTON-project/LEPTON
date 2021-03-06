/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

 /*-- Addition for pages_settings --*/
 function toggle_viewers() {
	if(document.settings.visibility.value == 'private' || document.settings.visibility.value == 'registered') {
		document.getElementById('allowed_viewers').style.display = 'block';
	} else {
		document.getElementById('allowed_viewers').style.display = 'none';
	}
}
var lastselectedindex = new Array();

function disabled_hack_for_ie(sel) {
	var sels = document.getElementsByTagName("select");
	var i;
	var sel_num_in_doc = 0;
	for (i = 0; i <sels.length; i++) {
		if (sel == sels[i]) {
			sel_num_in_doc = i;
		}
	}
	// never true for browsers that support option.disabled
	if (sel.options[sel.selectedIndex].disabled) {
		sel.selectedIndex = lastselectedindex[sel_num_in_doc];
	} else {
		lastselectedindex[sel_num_in_doc] = sel.selectedIndex;
	}
	return true;
}
/*-- Addition for remembering expanded state of pages --*/
function writeSessionCookie (cookieName, cookieValue) {
	document.cookie = escape(cookieName) + "=" + escape(cookieValue) + ";";
}

function toggle_viewers() {
	if(document.add.visibility.value == 'private') {
		document.getElementById('viewers').style.display = 'block';
	} else if(document.add.visibility.value == 'registered') {
		document.getElementById('viewers').style.display = 'block';
	} else {
		document.getElementById('viewers').style.display = 'none';
	}
}
function toggle_visibility(id){
	if(document.getElementById(id).style.display == "block") {
		document.getElementById(id).style.display = "none";
		writeSessionCookie (id, "0");//Addition for remembering expanded state of pages
	} else {
		document.getElementById(id).style.display = "block";
		writeSessionCookie (id, "1");//Addition for remembering expanded state of pages
	}
}
var plus = new Image;
plus.src = THEME_URL+"/images/plus_16.png";
var minus = new Image;
minus.src = THEME_URL+"/images/minus_16.png";
function toggle_plus_minus(id) {
	var img_src = document.images['plus_minus_' + id].src;
	if(img_src == plus.src) {
		document.images['plus_minus_' + id].src = minus.src;
	} else {
		document.images['plus_minus_' + id].src = plus.src;
	}
}

function add_child_page(page_id) {
	//find and select the page in the parent dropdown
	var selectBox = document.add.parent;
	var max = selectBox.options.length;
	for (var i = 0; i < max; i++) {
		if (selectBox.options[i].value == page_id) {
			selectBox.selectedIndex = i;
			break;
		}
	}
	//set focus to add form
	document.add.title.focus();
}