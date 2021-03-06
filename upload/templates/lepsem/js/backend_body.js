/**
 *  @template       LEPSem
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2018 cms-lab
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
 */

  /* confirm links */
 function confirm_link(message, url) {
	if(confirm(message)) location.href = url + "&amp;leptokh=#-!leptoken-!#";
 }
 
/**
 *  semantic functions
 */

/* sidebar navi */ 
$('.ui.sidebar')
   .sidebar('attach events', '.toc.item')
;

/* checkboxes */
$('.ui.checkbox')
  .checkbox()
;

/* settings accordion */
$('.ui.accordion')
  .accordion({
	closeNested: true
  })
;

/* initialize drop down buttons */
$('.ui.dropdown, ui.select.dropdown ')
  .dropdown({
    on: 'hover'
  })
; 

/* drag and drop inside pagetree */
$('#pagetree_sortable').sortable({
	// placeholder: "ui-state-highlight",
	// connectWith: ".sortable",
	update : function(event, ui ) {
	    // alert("CALL");
	    var sortedIDs = $( this ).sortable( "toArray" );
	    console.log( sortedIDs );
	    // Call via ajax the backend for update the position here:
	    // Function is inside "page_tree.lte"!
	    Lepsem_update_pagetree( sortedIDs );
	}
});

$('.sortable').disableSelection();

/* section sortable */
$("#page_sections_sortable").sortable({
//     connectWith: "#page_sections_sortable",
//     containment: "parent",
//     appendTo: 'parent',
    update : function(event, ui ) {
        var sortedIDs = $( this ).sortable( "toArray" );
        console.log( sortedIDs );
        // Call via ajax the backend for update the position here:
        Lepsem_update_sections( sortedIDs );
    }
});

$("#page_sections_sortable").disableSelection();

 /* force new password to confirm modifications in preferences */
$(function(){ 
	$('#submit').click(function() {
		if(!$('#current_password').val()) {
			alert( unescape('{{ TEXT.NEED_PASSWORD_TO_CONFIRM }}!') ); return false;
		}
	});
}); 

 /* close message boxes on click  */
$('.message .close')
  .on('click', function() {
    $(this)
      .closest('.message')
      .transition('fade')
    ;
  })
;


/**
 *  jQuery ui functions
 */

 /* enable datepicker    */
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  