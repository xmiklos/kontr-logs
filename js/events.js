$(document).ready(function() {

// initializations

KLogs.Loading.init();
KLogs.Filter.init();
KLogs.DetailedResults.init();
KLogs.Notif.init();
KLogs.Stats.update();
KLogs.FSLayer.init();

// event bindings

KLogs.Resubmission.bind();
KLogs.ManualSubmission.bind();

$("body").on("click", ".open_std", function(event){
  	$(this).parent().find(".odes").slideToggle('fast');
});

if (window.location.hash) {
    $(window.location.hash+" .open_std").trigger('click');
}

$("body").on("click", ".open_test_stats", function(event){
  	$(this).parent().find(".odes").slideToggle('fast');
});

$("body").on("click", ".run_test_list", KLogs.Run.test_change);
$("body").on("click", ".compile_button", KLogs.Run.compile);
$("body").on("click", ".run_button", KLogs.Run.run);
$("body").on("click", ".run_file_edit", KLogs.Edit.new_editor);
$("body").on('click', '.reset_files', KLogs.Run.reset);

$("body").on("click", ".show_in_details", function(event){
     KLogs.SubDetails.set_load_test($(this).data('test'));
  	 $("#"+$(this).data('timestamp')+" .open_details").trigger('click');
});

$(document).on({
    ajaxStart: function(e, h) {
    	KLogs.Loading.show();
    },
    ajaxStop: function() {
    	KLogs.Loading.hide();
    }
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
	KLogs.Ajax.get_tags();
	KLogs.Cookies.set('last_task', $(this).prop("selectedIndex"), 365);
	KLogs.Cookies.set('last_subject', $('select[name="subject"]').prop("selectedIndex"), 365);
});

$('#go_button').click(function()
{
	KLogs.Ajax.send();
	KLogs.Ajax.get_tags();
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

// details
$("body").on("click", ".open_details", function(e){
	KLogs.SubDetails.show(e);
});

$("body").on("click", ".details_show_file", function(e){
	KLogs.SubDetails.show_file(e);
});

// change subject
$("select[name='subject']").change(function(){
	KLogs.Ajax.get_tasks(KLogs.Ajax.get_tags);
});

// apply settings
KLogs.Settings.apply();

$(".open-system-logs").click(function()
{
	KLogs.FSLayer.show();
	KLogs.FSLayer.html('loading...');
	$.post("index.php", 
	{what: 'SystemLogs'},
	function(data)
	{
		var allow_manual = 0;
		KLogs.FSLayer.html(data);
		$("#system_logs_close").button().click(function( event ) 
		{
			KLogs.FSLayer.html('');
			KLogs.FSLayer.hide();
		});
		$("#system_logs_reload").button().click(function( event ) 
		{
			var active = $("#system_logs_tabs").tabs("option", "active");
			allow_manual = 1;
			$("#system_logs_tabs").tabs("load", active);
		});
		$("#system_logs_tabs").tabs({ heightStyle: "fill", load: function( event, ui ) {
			$("#system_logs_tabs div").scrollTo('max', 300, {axis:'y', onAfter: function(){
				KLogs.Loading.hide();
			}});
		}, 
		beforeLoad: function( event, ui ) {
			ui.jqXHR.error(function() {
			  if(!(ui.tab.data("loaded") && allow_manual == 0))
			  {
			  	ui.panel.html("Ajax Error!");
			  }
			});
			//$("#system_logs_tabs div").html('loading...')
			if (ui.tab.data("loaded") && allow_manual == 0) {
			    event.preventDefault();
			}
			ui.jqXHR.success(function() {
			    ui.tab.data("loaded", true);
			    allow_manual = 0;
			    KLogs.Loading.show();
			});
		    }
		});
  	}).fail(function(jqXHR, textStatus, errorThrown){
  		KLogs.FSLayer.hide();
  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
  	});
});


$(window).resize(function() {
  $("#system_logs_tabs").tabs("refresh");
  $("#diff_tabs").tabs("refresh");
  $("#details_tabs").tabs("refresh");
  $("#details_tests").tabs("refresh");
  $("#system_logs_tabs").tabs("refresh");
  
  KLogs.SubDetails.refresh();
  
  $(".details_action .ui-tabs-panel").css({float: "none", clear: "both"});
  $( ".test_li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
});

// tooltips

$(document).tooltip({ tooltipClass: "tooltip-styling", show: false, hide: false, position: { my: "left+15 center", at: "right center" } });

KLogs.GA.log_user();

});


