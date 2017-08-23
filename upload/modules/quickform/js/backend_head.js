/**
 *  Backend Javascript for Quickform
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
