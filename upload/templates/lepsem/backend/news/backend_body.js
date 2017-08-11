/**
 *  @template       LEPSem
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2017 cms-lab
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
 */


/**
 *  jQuery ui functions
 */

/* enable datepicker    */
$(function() {
  $( "#datepicker_start,#datepicker_end" ).datepicker({
    changeMonth: true,
    changeYear: true
  });
});
  
/* enable drag and drop plugin   */  

/* drag and drop inside news */
$('#sortable1').sortable({
	//placeholder: "ui-state-highlight",
	connectWith: "#sortable1",
	update : function(event, ui ) {
	    // alert("CALL");
	    var sortedIDs = $( this ).sortable( "toArray" );
	    console.log( sortedIDs );
	    // Call via ajax the backend for update the position here:
	    // Function is inside "page_tree.lte"!
	    news_update_order( sortedIDs );
	}
});

/**
 *  Needed for the $.sortable/drag as callback! 
 */
var xmlhttp;
if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
} else {
    // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}

function news_update_order( sString ) {
    
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4) {
            if (xmlhttp.status==200) {
               
               var message_box_message_ref = document.getElementById("news_reorder_messagebox_message");
               message_box_message_ref.innerHTML = xmlhttp.responseText;
               
               var message_box_ref = document.getElementById("news_reorder_messagebox");
               message_box_ref.style.display = "block";
               
               return true;
               // alert ("Response: "+xmlhttp.responseText);
            }
        }
    }

    xmlhttp.open("POST", LEPTON_URL+"/modules/news/update_news_order.php", true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("news="+sString+"&job=xre234");
}