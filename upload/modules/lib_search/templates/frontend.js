/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          lib_search
 * @author          LEPTON Project
 * @copyright       2013-2017 LEPTON Project
 * @link            https://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */

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