
var KLogs = window.KLogs || {};


// Ajax
KLogs.Ajax = (function() {

var all = false;
var req_sent = false;

return {
send: function(fn)
{
		var tutor = $('#tutorfilter').val();
		$('#students_wrapper').html('<h3>loading...</h3>');
		if($(".expand-all").html() == '[Collapse all]') $(".expand-all").trigger('click');
		$.post("index.php", {what: 'Logs', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(), tutor: tutor, all: "all"},function(data)
		{
			KLogs.Notif.get();
			if(tutor == "")
			{
				all = true;
			}
			else
			{
				all = false;
			}
			req_sent = true;
			$('#students_wrapper').html(data);
			$('#subfilter').prop("selectedIndex",0);
			KLogs.Filter.show_all();
			KLogs.Filter.students();
			KLogs.SubSelector.reset();
			if (KLogs.Cookies.check('logs_settings') && KLogs.Cookies.get('logs_settings').split(",")[1] == "true") $(".sort-alpha").trigger('click');
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		$('#students_wrapper').html('');
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
get_all: function()
{
	return all;
},
get_sent: function()
{
	return req_sent;
},
get_tasks: function(fn)
{
	$.post("index.php", {what: 'Tasks', subject: $('select[name="subject"]').val()},function(data)
		{
			$('select[name="task"]').html(data);
			$('#students_wrapper').html('');
			KLogs.Notif.get();
			KLogs.Stats.update();
			if (fn) fn();
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		$('#students_wrapper').html('');
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
get_tags: function(fn)
{
	$.post("index.php", {what: 'Tags', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val()},function(data)
		{
			$('#tagfilter').html(data);
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		$('#tagfilter').html('');
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
}

};

})();

// Display
KLogs.DetailedResults = (function() {

var detResElement = $("<div class='detailed_result' ></div>");
var pin = false;

var ret_obj = {};

ret_obj.detailedResultsOn = function(e)
{
	var results = $(e.target.firstChild).html();
	$(detResElement).html(results);
	$(detResElement).show();
}

ret_obj.detailedResultsOff = function(e)
{
	if(!pin) $(detResElement).hide();
}

ret_obj.bind = function()
{
	$('body').on("mouseenter", ".summary", KLogs.DetailedResults.detailedResultsOn)
	.on("mouseleave", ".summary", KLogs.DetailedResults.detailedResultsOff)
	.on("click", ".summary", function(){
		pin=!pin;
	});
	
}

ret_obj.init = function()
{
	$(detResElement).appendTo('body');
	KLogs.DetailedResults.bind();
}

return ret_obj;

})();

// Filter
KLogs.Filter = (function() {

var tutor_filter = '#tutorfilter';
var submission_filter = '#subfilter';
var student_filter = '#studfilter';
var tag_filter = '#tagfilter';

return {

students: function(all)
{
	var filter = $(tutor_filter).val()+$(student_filter).val();
	var sender = $(this).attr('id');
	
	if(sender == "tutorfilter")
	{
		if(!KLogs.Ajax.get_all() && KLogs.Ajax.get_sent())
		{
			KLogs.Ajax.send();
			return;
		}
	}
	
	if(filter == "")
	{
		$(".user").data("student_filter", true);
		KLogs.Filter.refresh();
		return;
	}
	
	$(".user").data("student_filter", false);
	$(filter).data("student_filter", true);
	
	KLogs.Filter.refresh();
},
refresh: function()
{
	$(".user").each(function(){
		if($(this).data("student_filter") && ($(this).data("student_tag_filter") || $(this).data("student_tag_filter") == undefined))
		{
			$(this).show();
		}
		else
		{
			$(this).hide();
		}
	});
	
	$(".ode").each(function(){
		if($(this).data("sub_filter") && ($(this).data("sub_tag_filter")))
		{
			$(this).show();
		}
		else
		{
			$(this).hide();
		}
	});
	
	KLogs.Stats.update();
},
submissions: function()
{	
	// basic filter
	var filter = $(submission_filter).val();
	
	if(filter == "")
	{
		$('.ode').data("sub_filter", true);
	}
	else
	{
		$('.ode').data("sub_filter", false);
		$(filter).data("sub_filter", true);
	}
	
	//tag filter
	var tfilter = $(tag_filter).val();
	
	if(tfilter == "none" || tfilter == "" || tfilter == undefined)
	{
		//KLogs.Filter.hide_empty();
		$('.ode').data("sub_tag_filter", true);
	}
	else
	{
		$('.ode').data("sub_tag_filter", true);
		$('.ode').each(function (i) {
		
			var tags_str = $(this).data('tags');
			var tags = tags_str.split(" ");
		
			for(var i=0; i < tags.length; i++)
			{
				if(tags[i] == tfilter) return;
			}
		
			$(this).data("sub_tag_filter", false);
		});
	}
	
	KLogs.Filter.hide_empty();
	KLogs.Filter.refresh();
},
hide_empty: function()
{
	$(".user").each(function (i) {
		var hide = true;
	
		$(this).find(".ode").each(function(i, value){
			//ak nie je odovzdanie skryte
			if($(value).data("sub_tag_filter") == true && $(value).data("sub_filter") == true)
			{
				// nie je ani student skryty
				hide = false;
				return;
			}
		});

		
		if(hide)
		{
			$(this).data("student_tag_filter", false);
		}
		else
		{
			$(this).data("student_tag_filter", true);
		}

	});
	
	//KLogs.Filter.students(false);
},
show_all: function()
{
	$(".user").data("student_filter", true);
	$('.ode').data("sub_filter", true);
	$('.ode').data("sub_tag_filter", true);
	$(".user").data("student_tag_filter", true);
	
},
bind: function()
{
	$(tutor_filter+','+student_filter).change(KLogs.Filter.students);
	$(submission_filter).change(KLogs.Filter.submissions);
	$("body").on("change", tag_filter, KLogs.Filter.submissions);
},
init: function()
{
	KLogs.Filter.bind();
}

};

})();

// Notifications
KLogs.Notif = (function() {

var notifElement = $("<div class='notif_box' ></div>");
var enabled = 0;
var id;
var interval = 120000;

return {

update: function()
{
	var uname = $(this).data('user');
	var id = "#u_"+uname;
	
	
		$.post("index.php", {what: 'Logs', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(), student: uname},function(data)
		{
			if($(id).length > 0)
			{
				$(id).replaceWith(data);
			}
			else
			{
				$('.test_stats').remove();
				$('#students_wrapper').append(data);
			}
			$(id+" .open_std").parent().find(".odes").show();
			$(id+" .std").promise().done(function()
			{
				$(document).scrollTo(id, 300);
				KLogs.Stats.update();
			});
	  	});
	
	
},
get: function()
{
	if(!enabled) return;
	$.post("index.php", {what: 'Notif', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val()},function(data)
		{
			$('.notif_box').html(data).scrollTo('max', 800);
			
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.Notif.toggle();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
toggle: function()
{
	if(enabled)
	{
		enabled = 0;
		window.clearInterval(id);
		$('.notif_box').scrollTop(0).hide().html('');
		$('.enable-notif').html('[Enable notifications]');
	}
	else
	{
		enabled = 1;
		$('.notif_box').show().html('loading...'); // todo task and subject
		KLogs.Notif.get();
		id=self.setInterval(function(){
			KLogs.Notif.get();
		}, interval);
	  	$('.enable-notif').html('[Disable notifications]');
	}
},
disable: function()
{
	if(enabled)
	{
		KLogs.Notif.toggle();
	}
},
bind: function()
{
	
},
init: function()
{
	$(notifElement).appendTo('body');
	KLogs.Notif.bind();
}

};

})();

// submission selector
KLogs.SubSelector = (function() {
	var selectors = '.submission_selector';
	var count_selected = 0;


return {
change_selection: function(e)
{
	if($(e.target).is(':checked'))
	{
		count_selected++;
	}
	else
	{
		if(count_selected > 0) count_selected--;
	}

},
get_count: function()
{
	return count_selected;
},
reset: function()
{
	$(selectors).attr('checked', false);
	count_selected = 0;
},
get_submission_selection: function()
{
	var str='';
	$(selectors).each(function (i) {
		if($(this).is(':checked')) {
			str += (str!=''?' ':'')+$(this).parent().data('login')+":"+
			+$(this).parent().data('revision')+":"+$(this).parent().data('type');
		}
	});
	
	return str;
},
get_folder_selection: function()
{
	var str='';
	$(selectors).each(function (i) {
		if($(this).is(':checked')) {
			str += (str!=''?' ':'')+$(this).parent().attr('id');
		}
	});
	
	return str;
}

};

})();

// diff
KLogs.Diff = (function() {
	


return {
do_diff: function()
{
	if(KLogs.SubSelector.get_count() != 2)
	{
		
		KLogs.Message.show("Please select exactly 2 submissions!", 3);
		KLogs.SubSelector.reset();
	}
	else
	{
		
		//KLogs.FSLayer.html('loading...');
		var subs = KLogs.SubSelector.get_folder_selection();
		$.post("index.php", 
		{what: 'Diff', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(), subs: subs},
		function(data)
		{
			KLogs.FSLayer.html(data);
			KLogs.FSLayer.show();
			$("#diff_tabs").tabs({ heightStyle: "fill" });
			$("#diff_close").button().click(function( event ) 
			{
				KLogs.FSLayer.hide();
				KLogs.SubSelector.reset();
			});
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.SubSelector.reset();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
	}
}

};

})();

// statistics
KLogs.Stats = (function() {
	var std_count = 0;
	var real = 0;
	var real6 = 0;


return {
update: function()
{
	std_count = 0;
	real = 0;
	real6 = 0;
	$('.user').each(function (i) {
		if($(this).is(':visible')) {
			std_count++;
			if($(this).hasClass('naostro'))
			{
				real++;
			}
			if($(this).hasClass('naostro6b'))
			{
				real6++;
			}
		}
	});
	
	$('.std_stat').html(std_count);
	$('.naostro_stat').html(real);
	$('.naostro6_stat').html(real6);
}

};

})();

// full screen layer
KLogs.FSLayer = (function() {

var layerElement = $("<div class='fs_layer' ></div>");

return {
show: function()
{
	$(layerElement).show();
	$('body').addClass('stop-scrolling');
},
html: function(data)
{
	$(layerElement).html(data);
},
hide: function()
{
	$(layerElement).hide();
	$('body').removeClass('stop-scrolling');
},
init: function()
{
	$(layerElement).appendTo('body');
}

};

})();

// message
KLogs.Message = (function() {

var el = '#f_message';
var el_text = '#f_message_text';

return {
show: function(data, time)
{
	$(el_text).html(data);
	$(el).show().delay(time*1000).fadeOut();
}

};

})();

// cookies
KLogs.Cookies = (function(){


return {
get: function(c_name)
{
	if (document.cookie.length>0)
	  {
	  c_start=document.cookie.indexOf(c_name + "=");
	  if (c_start!=-1)
	    {
	    c_start=c_start + c_name.length+1;
	    c_end=document.cookie.indexOf(";",c_start);
	    if (c_end==-1) c_end=document.cookie.length;
	    return unescape(document.cookie.substring(c_start,c_end));
	    }
	  }
	return "";
},
set: function(c_name,value,expiredays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+
	((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
},
check: function(c_name)
{
	username=KLogs.Cookies.get(c_name);
	if (username!=null && username!="")
	  {
	  	return true;
	  }
	else
	  {
		return false;
	  }
}

}

})();

KLogs.Settings = (function() {

return {
save: function()
{
	var a = [];
	$('.user_setting').each(function (i) {
		if($(this).is(':checked')) {
			a[i]=true;	
		}
		else
		{
			a[i]=false;
		}
	});
	
	KLogs.Cookies.set('logs_settings', a.toString(), 365);
},
apply: function()
{
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
		
		var login = $('#login').html();
		
		for(i=0; i<a.length; i++)
		{
			if(a[i] == "true")
			{
				switch(i)
				{
					case 0: if($(".user").length != 0) break;
						$("#tutorfilter option[value='."+login+"']").prop('selected', true); break;
					case 1: $(".sort-alpha").trigger('click'); break;
					case 2: if($(".user").length != 0) break;
						$("#studfilter").prop("selectedIndex", 3); break;
					case 3: 
						var b = $(".user");
						if($(".user").length != 0) break;
						var task_i = KLogs.Cookies.get('last_task');
						var subj_i = KLogs.Cookies.get('last_subject');
						$('select[name="subject"]').prop("selectedIndex", subj_i);
						KLogs.Ajax.get_tasks(function(){
							$('select[name="task"]').prop("selectedIndex", task_i).trigger('change');
						});
						break;
				}
			}
		}
	}
}

};

})();

// Details
KLogs.SubDetails = (function() {

var on_load_test = 0;

return {
show: function(e)
{
		//KLogs.FSLayer.html('loading...');
		var sub = $(e.target).parent().attr('id');
		
		$.post("index.php", {what: 'Details', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(),
		sub_folder: sub
		},function(data)
		{
			KLogs.FSLayer.show();
			KLogs.FSLayer.html(data);
                        KLogs.Run.set_folder(sub);
			$("#details_close").button().click(function( event ) 
			{
				KLogs.FSLayer.hide();
				KLogs.FSLayer.html('');
                KLogs.Run.unset();
                on_load_test = 0;
			});
			$("#details_tabs").tabs({ heightStyle: "fill", active: 0, activate: KLogs.SubDetails.refresh});
			$("#details_tests").tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    			$("#details_sources").tabs({load: KLogs.SubDetails.refresh}).addClass( "ui-tabs-vertical ui-helper-clearfix" );
    			$("#details_misc").tabs({load: KLogs.SubDetails.refresh}).addClass( "ui-tabs-vertical ui-helper-clearfix" );
    			$( ".test_li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    			
    			$(".details_action").tabs();
    			$(".details_action .ui-tabs-panel").css({float: "none", clear: "both"});
    			//$(".details_action li").removeClass("ui-corner-left");
    			$("#details_tests").tabs({ active: on_load_test})

	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
refresh: function()
{
	KLogs.SubDetails.sources_tab_refresh();
	KLogs.SubDetails.tests_tab_refresh();
	KLogs.SubDetails.misc_tab_refresh();
},
sources_tab_refresh: function()
{
	w = $("#details_sources").width();
    	panel_w = $(".details_sources_list").width();
    	$( "#details_sources .details_file_wrapper" ).width(w-panel_w-70);
},
misc_tab_refresh: function()
{
	w = $("#details_misc").width();
    	panel_w = $(".details_misc_list").width();
    	$( "#details_misc .details_file_wrapper" ).width(w-panel_w-70);
},
tests_tab_refresh: function()
{
	var w = $("#details_tests").width();
    	var panel_w = $(".details_tests_list").width();
    	$( "#details_tests .test_content" ).width(w-panel_w-70);
},
show_file: function(e)
{
	var dir = $(e.target).data('path');
	if(dir == undefined)
	{
		var dir = $(e.target).parents(".details_sum").data('path');
	}
	var sub = $(e.target).parents(".details_sum").data('sub');
	var filename = $(e.target).html();
	
	
	$.post("index.php", {what: 'Details', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(),
		sub_folder: sub, path: dir, file: filename
		},function(data)
		{
			$(e.target).parents(".details_sum").find(".details_ajax_data").html(data);
			var pos = $(e.target).parents(".details_sum").find(".details_ajax_data").position().top;
			$("#details_tests").scrollTo(pos+15, 400, 'y');

	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
set_load_test: function(number)
{
    on_load_test = number;
}

};

})();

// resubmission
KLogs.Resubmission = (function() {

return {
show_dialog: function()
{
	if(KLogs.SubSelector.get_count() != 1)
	{
		KLogs.Message.show("Please select exactly 1 submission!", 3);
	}
	else
	{
		var all_subs = KLogs.SubSelector.get_submission_selection();
		var t = $('select[name="task"]').val();
		var s = $('select[name="subject"]').val();
		var subs = all_subs.split(" ");
		var notice = "<p>You may only resubmit one submission at a time.</p><p>Only you will get emails with results.</p>";
		$('#submission_dialog').html(notice+"<p>Following submissions will be resubmitted:</p><div class='resub_list'></div>");
		var info='<strong>'+s+': '+t+'</strong>';

		for(var i=0; i < subs.length; i++)
		{
			var spl = subs[i].split(":");
			info += '<div>'+spl[0]+' - rev. '+spl[1]+'</div>';
			
		}
		
		$('.resub_list').html(info);
		
		$('#submission_dialog').dialog({
		resizable: false,
		modal: true,
		width: 500,
		dialogClass: "settings_dialog",
		buttons: {
			"Submit": function() {
				KLogs.Resubmission.submit();
				KLogs.SubSelector.reset();
				$(this).dialog("option", "buttons", { "Close": function(){
					$(this).dialog("destroy");
					$('#submission_dialog').hide();
				}});
			},
			"Cancel": function()
			{
				$(this).dialog("destroy").hide();
			}
		}
		});
		
	}
},
submit: function()
{
	$('.resub_list').html('sending...');
	$.post("index.php", { what: 'Submit', subject: $('select[name="subject"]').val(),
				task: $('select[name="task"]').val(),
				subs: KLogs.SubSelector.get_submission_selection(),
				type: "resubmit"
		},
		function(data)
		{
			$('#submission_dialog').html(data);
			
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		$('.resub_list').html('');
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
bind: function()
{
	$(".open-resub-dialog").click(KLogs.Resubmission.show_dialog);
}

};

})();

// make submission
KLogs.ManualSubmission = (function() {

return {
show_dialog: function()
{
	if(KLogs.SubSelector.get_count() <= 0)
	{
		KLogs.Message.show("Please select at least 1 submission!", 3);
	}
	else
	{
		var all_subs = KLogs.SubSelector.get_submission_selection();
		var t = $('select[name="task"]').val();
		var s = $('select[name="subject"]').val();
		var subs = all_subs.split(" ");
		var notice = "<p>You may only submit one submission at a time for each student.</p>";
		$('#submission_dialog').html(notice+"<p>Following submissions will be submitted:</p><div class='resub_list'></div>");
		var info='<strong>'+s+': '+t+'</strong>';

		for(var i=0; i < subs.length; i++)
		{
			var spl = subs[i].split(":");
			info += '<div>'+spl[0]+' - rev. '+spl[1]+'</div>';
			
		}
		
		$('.resub_list').html(info);
		
		$('#submission_dialog').dialog({
		resizable: false,
		modal: true,
		width: 500,
		dialogClass: "settings_dialog",
		buttons: {
			"Submit": function() {
				KLogs.ManualSubmission.submit();
				KLogs.SubSelector.reset();
				$(this).dialog("option", "buttons", { "Close": function(){
					$(this).dialog("destroy");
					$('#submission_dialog').hide();
				}});
			},
			"Cancel": function()
			{
				$(this).dialog("destroy").hide();
			}
		}
		});
		
	}
},
submit: function()
{
	$('.resub_list').html('sending...');
	$.post("index.php", { what: 'Submit', subject: $('select[name="subject"]').val(),
				task: $('select[name="task"]').val(),
				subs: KLogs.SubSelector.get_submission_selection(),
				type: "submit"
		},
		function(data)
		{
			$('#submission_dialog').html(data);
			
	  	}).fail(function(jqXHR, textStatus, errorThrown){
	  		$('.resub_list').html('');
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
bind: function()
{
	$(".open-mansub-dialog").click(KLogs.ManualSubmission.show_dialog);
}

};

})();

// loading
KLogs.Loading = (function() {

var loading_element = $("<div class='loading' ></div>");
var loading = 0;

return {
init: function()
{
	$("body").append(loading_element);
},
show: function()
{
	loading++;
	$('.loading').show();
},
hide: function()
{
	if(loading > 0) loading--;
	if(loading == 0)
	{
		$('.loading').hide();
	}
}

};

})();

KLogs.Run = (function() {

var params_obj = undefined;
var sub_folder = undefined;

return {
set_folder: function(folder)
{
	sub_folder = folder;
},
load_params: function()
{
    if(sub_folder == undefined)
    {
        KLogs.FSLayer.hide();
        KLogs.Message.show("Error: this should not happen!");
    }
    $.ajax({
  type: 'POST',
  url: "index.php",
  data: {what: 'Run', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(),
		sub_folder: sub_folder},
  success: function(data)
		{
                    params_obj = jQuery.parseJSON(data);
	  	},
  async:false
}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
test_change: function(e)
{
    if(params_obj == undefined)
    {
        KLogs.Run.load_params();
    }
    //alert(params_obj[0].name);
    var test = $(e.target).data('test');
    //alert(test);
    var params=undefined;
    for(var i = 0; i < params_obj.length; i++)
    {
        if(params_obj[i].name == test)
        {
            params = params_obj[i];
            break;
        }
    }
    
    if(params == undefined)
    {
        alert("ERROR: Test not found!");
        return;
    }
    $(".run_file_selector").prop('checked', false);
    for(var i = 0; i < params.files.length; i++)
    {
        var file = params.files[i];
        $(".run_file_selector").each(function(){
            var runfile = $(this).data('file');
            
            if(file == runfile)
            {
                $(this).prop('checked', true);
            }
        })
    }
    
    $(".cmpl_flags").val(params.cplt_flags);
    $(".stdin_file").val(params.stdin);
    $(".run_args").val(params.run_args);
},
unset: function()
{
    params_obj = undefined;
    sub_folder = undefined;
},
compile: function()
{
    $.ajax({
  type: 'POST',
  url: "index.php",
  data: {what: 'Run', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(),
		sub_folder: sub_folder, compile: "compile", cmpl_files: KLogs.Run.get_selected(),
            cmpl_flags: $(".cmpl_flags").val()},
  success: function(data)
		{
                    $(".run_output").html(data);
	  	},
  async:true
}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
get_selected: function()
{
    var buffer="";
    $(".run_file_selector").each(function(){
            
            if($(this).prop('checked'))
            {
                buffer += " "+$(this).data('file');
            }
        });
        return buffer.substr(1);
},
run: function()
{
    var grind = "";
    if($(".use_grind").is(':checked'))
    {
        grind = "valgrind";
    }
    $.ajax({
  type: 'POST',
  url: "index.php",
  data: {what: 'Run', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(),
		sub_folder: sub_folder, run: "run", run_args: $(".run_args").val(),
            stdin_file: $(".stdin_file").val(), valgrind: grind},
  success: function(data)
		{
                    $(".run_output").html(data);
	  	},
  async:true
}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
},
reset: function()
        {
            $.ajax({
  type: 'POST',
  url: "index.php",
  data: {what: 'Run', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(),
		sub_folder: sub_folder, reset: "reset"},
  success: function(data)
		{
                    $(".reset_message").html(data);
	  	},
  async:true
}).fail(function(jqXHR, textStatus, errorThrown){
	  		KLogs.FSLayer.hide();
	  		KLogs.Message.show(textStatus + " - " + errorThrown, 4);
	  	});
        }

};

})();


KLogs.Edit = (function() {

var loading_element = $("<div class='loading' ></div>");
var loading = 0;

return {
new_editor: function(e)
{
	var key = $(e.target).data('file');
        /*var id = key.replace(".", "_");
        var cl = key.replace(".", "_");
        $("#details_tabs").find( ".ui-tabs-nav:first" ).append("<li class='"+cl+"'><a href='#"+id+"'>"+key+"</a><span class='ui-icon ui-icon-close' data-cl='"+cl+"' role='presentation'>Remove Tab</span></li>");
        $("#details_tabs").append("<div id='"+id+"'>bla</div>");
        $("#details_tabs").tabs("refresh");
        
        $("#details_tabs").delegate( "span.ui-icon-close", "click", function(e) {
        var cls = $(e.target).data('cl');
        $("."+cls).remove();
        $( "#" + cls ).remove();
        $("#details_tabs").tabs( "refresh" );
        });*/
}

};

})();
