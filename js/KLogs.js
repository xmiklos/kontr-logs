
var KLogs = window.KLogs || {};


// Ajax
KLogs.Ajax = (function() {

var all = false;
var req_sent = false;

return {
send: function()
{
		var tutor = $('#tutorfilter').val();
		$('#students_wrapper').html('<h3>loading...</h3>');
		if($(".expand-all").html() == '[Collapse all]') $(".expand-all").trigger('click');
		$.post("index.php", {what: 'LogsCommand', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(), tutor: tutor},function(data)
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
			KLogs.Stats.update();
			KLogs.Filter.students();
			if (KLogs.Cookies.check('logs_settings') && KLogs.Cookies.get('logs_settings').split(",")[1] == "true") $(".sort-alpha").trigger('click');
	  	}).fail(function(){
	  		$('#students_wrapper').html('');
	  		KLogs.Message.show("Ajax Error! Try again.", 3);
	  	});
},
get_all: function()
{
	return all;
},
get_sent: function()
{
	return req_sent;
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

return {

students: function()
{
	var filter = $(tutor_filter).val()+$(student_filter).val();
	
	var sender = $(this).attr('id');
	
	if(sender == "tutorfilter")
	{
		if(!KLogs.Ajax.get_all() && KLogs.Ajax.get_sent())
		{
			KLogs.Ajax.send();
		}
	}
	
	if(filter == "")
	{
		$('.user').show();
		KLogs.Stats.update();
		return;
	}
	
	$('.user').hide();
	$(filter).show();
	KLogs.Stats.update();
},
submissions: function()
{
	var filter = $(submission_filter).val();
	
	if(filter == "")
	{
		$('.ode').show();
		return;
	}
	$('.ode').hide();
	
	$(filter).show();
	
	
},
bind: function()
{
	$(tutor_filter+','+student_filter).change(KLogs.Filter.students);
	$(submission_filter).change(KLogs.Filter.submissions);
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
	
		$.post("index.php", {what: 'LogsCommand', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(), student: uname},function(data)
		{
			if($(id).length > 0)
			{
				$(id).replaceWith(data);
			}
			else
			{
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
	$.post("index.php", {what: 'NotifCommand', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val()},function(data)
		{
			$('.notif_box').html(data).scrollTo('max', 800);
			
	  	}).fail(function(){
	  		KLogs.Notif.toggle();
	  		KLogs.Message.show("Ajax Error! Try again.", 3);
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
get_selection: function()
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
		KLogs.FSLayer.show();
		KLogs.FSLayer.html('loading...');
		var subs = KLogs.SubSelector.get_selection();
		$.post("index.php", 
		{what: 'DiffCommand', task: $('select[name="task"]').val(), subject: $('select[name="subject"]').val(), subs: subs},
		function(data)
		{
			KLogs.FSLayer.html(data);
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
					case 0: $("#tutorfilter option[value='."+login+"']").prop('selected', true); break;
					case 1: $(".sort-alpha").trigger('click'); break;
					case 2: $("#studfilter").prop("selectedIndex", 3); break;
					case 3: 
						var task_i = KLogs.Cookies.get('last_task');
						var subj_i = KLogs.Cookies.get('last_subject');
						$('select[name="subject"]').prop("selectedIndex", subj_i);
						$('select[name="task"]').prop("selectedIndex", task_i).trigger('change');
						break;
				}
			}
		}
	}
}

};

})();

KLogs.SubDetails = (function() {

return {
show: function()
{
	
		KLogs.FSLayer.show();
		KLogs.FSLayer.html('loading...');
	  	KLogs.FSLayer.hide();
	  	KLogs.Message.show("TO DO!", 3);

}

};

})();
