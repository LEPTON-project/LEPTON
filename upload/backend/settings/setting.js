/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */
 
function change_mailer(type) {
	if(type == 'smtp') {
		document.getElementById('row_mailer_smtp_settings').style.display = '';
		document.getElementById('row_mailer_smtp_host').style.display = '';
		document.getElementById('row_mailer_smtp_auth_mode').style.display = '';
		document.getElementById('row_mailer_smtp_username').style.display = '';
		document.getElementById('row_mailer_smtp_password').style.display = '';
		if( document.settings.mailer_smtp_auth.checked == true ) {
			document.getElementById('row_mailer_smtp_username').style.display = '';
			document.getElementById('row_mailer_smtp_password').style.display = '';
		} else {
			document.getElementById('row_mailer_smtp_username').style.display = 'none';
			document.getElementById('row_mailer_smtp_password').style.display = 'none';
		}
	} else if(type == 'phpmail') {
		document.getElementById('row_mailer_smtp_settings').style.display = 'none';
		document.getElementById('row_mailer_smtp_host').style.display = 'none';
		document.getElementById('row_mailer_smtp_auth_mode').style.display = 'none';
		document.getElementById('row_mailer_smtp_username').style.display = 'none';
		document.getElementById('row_mailer_smtp_password').style.display = 'none';
	}
}

function toggle_mailer_auth() {
	if( document.settings.mailer_smtp_auth.checked == true ) {
		document.getElementById('row_mailer_smtp_username').style.display = '';
		document.getElementById('row_mailer_smtp_password').style.display = '';
	} else {
        document.settings.mailer_smtp_auth.value = 'false';
		document.getElementById('row_mailer_smtp_username').style.display = 'none';
		document.getElementById('row_mailer_smtp_password').style.display = 'none';
	}
}

function send_testmail(URL) {
    var xmlHttp = null;
    try {
        // Firefox, Internet Explorer 7. Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer 6.
        try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                alert("Your browser does not support AJAX!");
                return false;
            }
        }
    }

    xmlHttp.open("POST", URL, true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");

    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4) {
            try {
                // Get the data from the server's response
                if ( xmlHttp.responseText != "" ) {
                    document.getElementById("ajax_response").innerHTML=xmlHttp.responseText;
                    document.getElementById("ajax_response").style.display='block';
                }
            }
            catch (e) {
                alert("JavaScript error! Maybe your browser does not support AJAX!");
                return false;
            }
            xmlHttp=null;
        }
    }
    xmlHttp.send();
}

/**	*************
 *	SMTP settings (new from Aldus for L* 3 (tau-myon)
 */
function lepsem_init_smtp() {
	var ref = document.getElementById("mailer_routine_phpmailx");
	if(ref) {
		if(ref.checked == true) {
			for(var i=1; i<=3; i++) {
				document.getElementById("smtp_settings_"+i).style.display="none";
			}
			document.getElementById("smtp_settings_4").style.display = "none";
			document.getElementById("smtp_settings_5").style.display = "none";
		}
	}
}

lepsem_toggle_smtp_auth();
lepsem_init_smtp();

function lepsem_toggle_smtp( onOrOff ) {
	var ref = document.getElementById("mailer_routine_phpmailx");
	if(ref) {
		var now_display = (onOrOff == 0) ? "none" : "inherit";
		for(var i=1; i<=3; i++) {
			document.getElementById("smtp_settings_"+i).style.display = now_display;
		}
		// alert(document.getElementById("mailer_smtp_auth").checked);
		now_display = (document.getElementById("mailer_smtp_auth").checked == false) ? "none" : "inherit";
		document.getElementById("smtp_settings_4").style.display = now_display;
		document.getElementById("smtp_settings_5").style.display = now_display;
		
		//lepsem_toggle_smtp_auth();
	}
}

function lepsem_toggle_smtp_auth() {
	var ref = document.getElementById("mailer_smtp_auth");
	if(ref) {
		var now_display = (ref.checked == true) ? "inherit" : "none" ;
		document.getElementById("smtp_settings_4").style.display = now_display;
		document.getElementById("smtp_settings_5").style.display = now_display;
	}
}