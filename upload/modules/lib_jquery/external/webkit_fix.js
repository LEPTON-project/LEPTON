/**
 *  @template       Algos Backend-Theme
 *  @version        see info.php of this template
 *  @author         Jurgen Nijhuis & Ruud Eisinga, Dietrich Roland Pehlke
 * @copyright       2010-2017 Jurgen Nijhuis & Ruud Eisinga, Dietrich Roland Pehlke
 *  @license        GNU General Public License
 *  @license terms  see info.php of this template
 *  @platform       LEPTON, see info.php of this template
 *  @requirements   PHP 5.2.x and higher
 */
/* This file is deprecated and will be deleted together with Algos BE-Theme */
jQuery(document).ready(function()
{
	if ( $.browser.webkit || $.browser.safari )
	{
		$('input[type=text]').eq(0).focus();
		var _interval = window.setInterval(function ()
		{
			var autofills = $('input:-webkit-autofill');
			if (autofills.length > 0)
			{
				window.clearInterval(_interval); // stop polling
				autofills.each(function()
				{
					var clone = $(this).clone(true, true);
					$(this).after(clone).remove();
				});
				$('input[type=text]').eq(0).focus();
			}
		}, 200);
	}
	else
	{
		$('input[type=text]').eq(0).focus();
	}
});