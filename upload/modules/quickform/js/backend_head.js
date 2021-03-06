/**
 *
 *	@module			quickform
 *	@version		see info.php of this module
 *	@authors		Ruud Eisinga, LEPTON project
 *	@copyright		2012-2018 Ruud Eisinga, LEPTON project
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *
 */
 
$(function() {
	{% if (false == del) %} $(".msgtable").hide(); {% endif %}
	$(".msgtable .msg").hide(); 
	$(".msgtable td.line").click(function(){
		$(this).children(".msg").slideToggle();
    });
	$(".recved").click(function(){
		$(".msgtable").toggle();
		return false;
	});
    $("select.templates").on("change", function() {
        var link = $(this).parent().find("a.manage");
        link.attr("href", "{{ manage_url }}" + $(this).val());
    });
});
