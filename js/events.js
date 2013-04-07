$(document).ready(function() {

// initializations

KLogs.Filter.init();
KLogs.DetailedResults.init();
KLogs.Notif.init();
KLogs.Stats.update();
KLogs.FSLayer.init();

// event bindings

$("body").on("click", ".open_std", function(event){
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
	
	// todo refresh numbers if any
});

// notifications
$(".enable-notif").click(KLogs.Notif.toggle);
$("body").on("click", ".update_student", KLogs.Notif.update);

// submission selector bind
$("body").on("change", ".submission_selector", KLogs.SubSelector.change_selection);


// do diff button
$(".do-diff").click(KLogs.Diff.do_diff);

// display main panel with panel enabler
$('#panel_enabler').mouseenter(function(e){
	if(e.pageX == 0 && e.pageY == $(document).scrollTop()) 
		return; // to avoid chromium bug on linux - http://goo.gl/tBhBZ
	$("#head").css( "position","fixed" );
	$("#space_filler").show();
});

// hide main panel with on window scroll
$(window).scroll(function()
{
	if($(document).scrollTop() > 0)
	{
		$("#space_filler").hide();
		$("#head").css( "position","relative" );
	}
})

// ajax request student data
$('select[name="task"]').change(function()
{
	KLogs.Ajax.send();
	KLogs.Cookies.set('last_task', $(this).prop("selectedIndex"), 365);
	KLogs.Cookies.set('last_subject', $('select[name="subject"]').prop("selectedIndex"), 365);
});

$('#go_button').click(function()
{
	KLogs.Ajax.send();
	KLogs.Cookies.set('last_task', $('select[name="task"]').prop("selectedIndex"), 365);
	KLogs.Cookies.set('last_subject', $('select[name="subject"]').prop("selectedIndex"), 365);
});

// settings dialog
$('.open-settings').click(function(){
	
	if(KLogs.Cookies.check('logs_settings'))
	{
		var settings = KLogs.Cookies.get('logs_settings');
		var a = settings.split(",");
		$('.user_setting').each(function (i) {
			if(a[i] == "true") {
				$(this).prop('checked', true);	
			}
			else
			{
				$(this).prop('checked', false);	
			}
		});
	}

	$('#settings_dialog').dialog({
	resizable: false,
	modal: true,
	width: 500,
	dialogClass: "settings_dialog",
	buttons: {
		"Save": function() {
			KLogs.Settings.save();
			$(this).dialog( "destroy" );
		}
	},
	close: function(){
		$('.user_setting').prop('checked', false);
	}
    });
});

$("body").on("click", ".open_details", function(){
	KLogs.SubDetails.show();
});

// apply settings
KLogs.Settings.apply();

});


