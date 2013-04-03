
var KLogs = window.KLogs || {};

KLogs.Display = (function() {

var detResElement = $("<div class='detailed_result' ></div>");
var pin = false;

ret_obj = {};

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
		return;
	}
	
	$('.user').hide();
	$(filter).slideDown();
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





