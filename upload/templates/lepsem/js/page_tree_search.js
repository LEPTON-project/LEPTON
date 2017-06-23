/**
 *	Linked by 
 *	- page_overview.lte
 *	- page_search.lte
 *
 *	NOTICE: keep in mind that the requested vars are definde inside 
 *			the form inside
 *				- page_search_request.lte
 *
 */
 
var xmlhttp;
if (window.XMLHttpRequest) {
	// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
} else {
	// code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}

function page_tree_search() {
	var temp_searchtext = document.getElementById("lepton_pagetree_search_terms").value;
	
	var temp_ref = document.getElementById("lepton_pagetree_search_scope");
	var temp_searchtype = temp_ref.options[ temp_ref.selectedIndex ].value;
	
	var temp_leptoken = document.getElementById("lepton_pagetree_search_leptoken").value;
	
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			if (xmlhttp.status==200) {
				eval("var HTML_result = "+xmlhttp.responseText+";");
				// alert( HTML_result);
				var div_ref = document.getElementById("search_page_results_box");
				if(div_ref){
					div_ref.innerHTML = HTML_result;
				}
			} else {
				alert("Error [-23]: "+xmlhttp.status);
			}
		}
	} 	

	xmlhttp.open("POST","../../backend/pages/search_pagetree.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("searchtext="+temp_searchtext+"&searchtype="+temp_searchtype+"&leptoken="+temp_leptoken);
}