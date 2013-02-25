/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 * 
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2013 LEPTON Project
 * @link            http://www.lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @version         $Id: search.box.js 1591 2012-01-03 18:23:12Z phpmanufaktur $
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