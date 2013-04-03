

$(document).ready(function() {
  
  
  $("body").on("click", ".std", function(event){
  
  	$(this).parent().find(".odes").slideToggle('fast');
});

KLogs.Filter.init();
KLogs.Display.init();
  
  
});
