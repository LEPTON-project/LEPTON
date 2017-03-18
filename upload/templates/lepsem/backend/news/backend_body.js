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
$(document).ready(function() {
    // Initialise the table
    $("#news_table1,#news_table2").tableDnD();
});
