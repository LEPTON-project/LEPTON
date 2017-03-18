/**
 *  @module         code2
 *  @version        see info.php of this module
 *  @authors        Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @copyright      2004-2017 Ryan Djurovich, Chio Maisriml, Thomas Hornik, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */

function gethinttext(whatis, lang) {
	var t = "";
	switch (lang) {
		case 'DE':
			switch(whatis) {
				case '1':
					t = "HTML: <b>Ihre Eingabe</b>";
					break;
				case '2': t = "Javascript: <span class='info_not'>&lt;script type=&quot;text/javascript&quot;&gt;</span><b> Ihre Eingabe </b><span class='info_not'>&lt;/script&gt;</span>";
					break;
				case '3': t = "Interner Kommentar, erscheint nicht auf der Website.";
					break;
				case '4': t = "<font color='#990000'>Wie interner Kommentar, aber kann nur von Admins bearbeitet werden.</font>";
					break;
				default:
					t = "PHP: <span class='info_not'>&lt;?php</span><b> Ihre Eingabe </b><span class='info_not'> ?&gt;</span>";
			}
			break;
	
		default:
			switch(whatis) {
				case '1':	t = "HTML: <b>your input</b>";
					break;
				case '2': t = "Javascript: <span class='info_not'>&lt;script type=&quot;text/javascript&quot;&gt;</span><b> your input </b><span class='info_not'>&lt;/script&gt;</span>";
					break;
				case '3': t = "Internal Comment: for internal notes only, does not appear on website";
					break;
				case '4': t = "<font color='#990000'>(HTML) Like Internal Comment, but only an admin can edit this.</font>";
					break;
				default:
					t = "PHP: <span class='info_not'>&lt;?php</span><b> your input </b><span class='info_not'> ?&gt;</span>";
			}
	}
	return t;
}
/**
 *	@param integer A section ID
 */
function code2_change_code( iSectionID, sLanguage ) {

	var tempFormRef = document.getElementById("codeform"+iSectionID);
	
	var whatis = tempFormRef.whatis.options[ tempFormRef.whatis.selectedIndex].value;
	var t = gethinttext(whatis, sLanguage);	
	document.getElementById("infotext_output"+iSectionID ).innerHTML  = t;
	
	var sClass = (document.getElementById("code2_mode_"+iSectionID).selectedIndex == 0) ? " code2_smart" : " code2_full";
	
	tempFormRef.content.className = "content_"+whatis+sClass;
}

function code2_change_mode ( iSectionID, aRef) {
	var tempFormRef = document.getElementById("codeform"+iSectionID);
	
	if (tempFormRef) {
		var whatis = tempFormRef.whatis.options[ tempFormRef.whatis.selectedIndex].value;
		var sClass = (document.getElementById("code2_mode_"+iSectionID).selectedIndex == 0) ? " code2_smart" : " code2_full";
		tempFormRef.content.className = "content_"+whatis+sClass;
	}
}
