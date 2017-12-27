<?php
/**
 * This file is part of LEPTON Core, released under the GNU GPL
 * Please see LICENSE and COPYING files in your package for details, specially for terms and warranties.
 *
 * NOTICE:LEPTON CMS Package has several different licenses.
 * Please see the individual license in the header of each single file or info.php of modules and templates.
 *
 * @author          LEPTON Project
 * @copyright       2010-2018 LEPTON Project
 * @link            https://lepton-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

/**
 *  html code for donation, support and login
 */
 ?>
	<div class="ui basic segment">
		<div class="spacer"></div>
		<h4 class="ui header">Please consider a donation to LEPTON</h4>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" type="hidden" value="_s-xclick" /> 
			<input name="hosted_button_id" type="hidden" value="DF6TFNAE7F7DJ" /> 
			<input alt="PayPal &mdash; The safer, easier way to donate online." name="submit" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" type="image" /> 
			<img src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
		</form>		
		<div class="spacer"></div>	
		<div class="spacer"></div>	
		<div class="spacer"></div>			
		<div class="column">
			<div class="ui buttons">
				<a href='https://www.lepton-cms.org/english/contact.php' target='_blank'><button class="ui orange button">support LEPTON</button></a>
				<div class="or" data-text=" and "> </div>
				<a href="<?php echo ADMIN_URL; ?>/login/index.php"><button class="ui positive button">login to check installation</button></a>
			</div>
		</div>			

		<div class="spacer"></div>		
	</div>
