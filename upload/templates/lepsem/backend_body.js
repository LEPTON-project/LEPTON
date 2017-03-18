/**
 *  @template       LEPSem
 *  @version        see info.php of this template
 *  @author         cms-lab
 *  @copyright      2014-2017 cms-lab
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
  .accordion()
;

 /* initialize drop down buttons */
$('.ui.dropdown, ui.select.dropdown ')
  .dropdown({
    on: 'hover'
  })
; 

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
  