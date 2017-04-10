/**
 *  @template       LEPTON-Start
 *  @version        see info.php of this template
 *  @author         cms-lab
 * @copyright       2010-2017 CMS-LAB
 *  @license        http://creativecommons.org/licenses/by/3.0/
 *  @license terms  see info.php of this template
 *  @platform       see info.php of this template
 */


/* frontend javascript for lib_search */

function search_box_onfocus(input, search_string) {
  if (input.value == search_string){
    input.value='';
    input.className='search_box_input_active';
  } 
  else {
    input.select();
  }
}

function search_box_onblur(input, search_string) {
  if (input.value==''){ 
    input.value = search_string;
    input.className = 'search_box_input';
  }
}