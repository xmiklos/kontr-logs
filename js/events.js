$(document).ready(function() {

// initializations

KLogs.Filter.init();
KLogs.Display.init();
KLogs.Notif.init();
KLogs.Stats.update();

// event bindings

$("body").on("click", ".std", function(event){
  	$(this).parent().find(".odes").slideToggle('fast');
});

// expand/collapse button
$(".expand-all").click(function(e){

	var el = $(".expand-all");

	if(el.html() == '[Expand all]')
	{
		$('.odes').show();
		el.html('[Collapse all]');
	}
	else
	{
		$('.odes').hide();
		el.html('[Expand all]');
	}
	
	_gaq.push(['_trackEvent', 'Display', 'Expand - Collapse']);
});

// lexicographic sort
$(".sort-alpha").click(function(e){
	$('.user').sortElements(function(a, b){
	    return a.id > b.id ? 1 : -1;
	});
	
	// todo refresh numbers
});

// notifications
$(".enable-notif").click(KLogs.Notif.toggle);

// submission selector bind
$("body").on("change", ".submission_selector", KLogs.SubSelector.change_selection);


// do diff button
$(".do-diff").click(KLogs.Diff.do_diff);

// display main panel with panel enabler
$('#panel_enabler').mouseover(function(){
	$("#head").css( "position","fixed" );
	$("#space_filler").show();
});
$(window).scroll(function()
{
	if($(document).scrollTop() > 0)
	{
		$("#space_filler").hide();
		$("#head").css( "position","relative" );
	}
})

});
