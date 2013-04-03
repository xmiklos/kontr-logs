
var KLogs = window.KLogs || {};


// Display
KLogs.Display = (function() {

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
	$('.summary')
	.mouseenter(KLogs.Display.detailedResultsOn)
	.mouseleave(KLogs.Display.detailedResultsOff)
	.click(function(){
		pin=!pin;
		});
	
}

ret_obj.init = function()
{
	$(detResElement).appendTo('body');
	KLogs.Display.bind();
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

return {

update: function()
{
	
},
toggle: function()
{
	if(enabled)
	{
		enabled = 0;
		$('.notif_box').scrollTop(0).hide().html('');
	}
	else
	{
		$('.notif_box').show().html('loading...'); // todo task and subject
		$.post("index.php", {what: 'NotifCommand', task: 'hw03', subject: 'pb071'},function(data)
		{
			$('.notif_box').html(data).scrollTo('max', 800);
			enabled = 1;
	  	});
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

KLogs.Diff = (function() {
	


return {
do_diff: function()
{
	if(KLogs.SubSelector.get_count() != 2)
	{
		
		alert("Select exactly 2 submissions!");
		KLogs.SubSelector.reset();
	}
	else
	{
		// todo diff
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
