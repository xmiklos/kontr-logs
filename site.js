function tooogle(idelem) {
	var layer = document.getElementById(idelem);
		$(layer).slideToggle("fast");
}

function toogle(elem) {
	
		$(elem).slideToggle("fast");
}

function showe(elem) {
	
		$(elem).show();
}

function hidee(elem) {
	
		$(elem).hide();
}

function poslat()
{
	var x=document.getElementById("vyber").submit();
}

function getMouseCoords(e) {
					var coords = {};
					if (!e) var e = window.event;
					if (e.pageX || e.pageY) 	{
						coords.x = e.pageX;
						coords.y = e.pageY;
					}
					else if (e.clientX || e.clientY) 	{
						coords.x = e.clientX + document.body.scrollLeft
							+ document.documentElement.scrollLeft;
						coords.y = e.clientY + document.body.scrollTop
							+ document.documentElement.scrollTop;
					}
					return coords;
				}
	
	function med_mouseMoveHandler(e, desc_string){
		var coords = getMouseCoords(e);
		med_showDescription(coords, desc_string);
	}
	
	var descript = 1;
	
	function med_closeDescription(force){
	
		var layer = document.getElementById("description");
		//$(layer).fadeOut("normal");
		if(descript || force) layer.style.display = "none";
	}
	
	function med_init(){
		layer = document.createElement("div");
		//layer.style.width = 300 + "px";
		layer.style.position = "fixed";
		layer.style.zIndex = 10;
		layer.style.display = "none";
		layer.style.backgroundColor = "#ffffff";
		layer.style.border = "solid 2px #114400";
		layer.style.borderTopWidth = "6px";
		layer.style.color = "#000000";
		layer.style.padding = "6px";
		layer.style.wordWrap="break-word";
		layer.style.maxHeight = "80%";
		layer.style.overflow = "auto";
		layer.id = "description";
		document.body.appendChild(layer);
	}
	
	function med_showDescription(coords, desc_string){
		var layer = document.getElementById("description");
		layer.style.top = 28 + "px";//(coords.y + 25)+ "px";
		layer.style.left = 850 + "px";//(coords.x - 20) + "px";
		layer.style.maxWidth = ($(window).width() - 900) + "px";
		//$(layer).show("fast");
		layer.style.display = "block";
		layer.innerHTML = desc_string;
		

	}
	
	function med_disable_des_hide()
	{
		descript = !descript;
	}



function element(strid) {
var layer = document.createElement("div");
		//layer.style.zIndex = 999;
		layer.style.display = "none";
		layer.id = strid;
		//layer.style.position = "fixed";
		document.body.appendChild(layer);
}

function checkchange(el)
{
	if(el.checked)
	{
		if(el.id == 'cmplflags')
		{
			document.getElementById("cmplflagss").disabled = false;
		}
		if(el.id == 'stdin')
		{
			document.getElementById("stdinfile").disabled = false;
		}
	}
	else
	{
		if(el.id == 'cmplflags')
		{
			document.getElementById("cmplflagss").disabled = true;
		}
		if(el.id == 'stdin')
		{
			document.getElementById("stdinfile").disabled = true;
		}
	}
}


function showcode(predmet, uloha, folder) {
	element("showcode");
	var layer = document.getElementById("showcode");
	var text = '<div id="code">Choose file</div><div id="lefts"><div id="cls" class="cp" onclick="closeoptions()">[close]</div><div id="sources">loading...</div><fieldset><legend class="cp" onclick="init_compl_mode(\''+folder+'\')">[edit&compile&run]</legend><div id="compl"><fieldset><legend><div class="cp" onclick="compile()">[compile]</div></legend>additional flags:<br /><input type="checkbox" id="cmplflags" onchange="checkchange(this)"/><input type="text" id="cmplflagss" disabled /></fieldset><fieldset><legend><div class="cp" onclick="run()">[run]</div></legend>with valgrind: <input type="checkbox" id="rungrind" /><br />std input file:<br /><input type="checkbox" id="stdin" onchange="checkchange(this)" /><input type="text" id="stdinfile" disabled /><br />arguments:<br /><input type="text" id="params" /></fieldset><fieldset><legend><div class="cp" onclick="newfile()">[create new file]</div></legend>new file filename:<br /><input type="text" id="newfilename" /></fieldset></div></fieldset></div>';
	layer.innerHTML = text;

	$.post("loaddir.php", { uloha: uloha, predmet: predmet, odevzdani: folder},
  function(data){
		var lay = document.getElementById("sources");
		lay.innerHTML = data;
  });
	var layer = document.getElementById("showcode");
		toogle(layer);
$('body').addClass('stop-scrolling')
var lay = document.getElementById("code");
$(lay).height($(window).height()-2);

var l_close = document.getElementById("cls");
var l_src = document.getElementById("sources");

$(lay).width(($(window).width()-405));

_gaq.push(['_trackEvent', 'Sources', 'Display sources', folder]);

}

function compile()
{
	var lay = document.getElementById("code");
	var flags = document.getElementById("cmplflags").checked;
	var flagsdata = document.getElementById("cmplflagss").value;
	lay.style.overflowY = 'scroll';
	lay.innerHTML = 'compiling...';
	$.post("compile.php", { files: getCookie('compile_files'), flags: (flags?flagsdata:'')},
  function(data){
		lay.innerHTML = data;
  });
  
  _gaq.push(['_trackEvent', 'Sources', 'Compile']);

}

function run()
{
	var lay = document.getElementById("code");
	var inp = document.getElementById("stdin").checked;
	var grind = document.getElementById("rungrind").checked;
	var input = document.getElementById("stdinfile").value;
	var params = document.getElementById("params").value;
	lay.style.overflowY = 'scroll';
	lay.innerHTML = 'executing...';
	$.post("run.php", {valgrind: grind, inputfile: (inp?input:''), args: params},
  function(data){
		lay.innerHTML = data;
  });
  
  _gaq.push(['_trackEvent', 'Sources', 'Run']);

}

function init_compl_mode(folder)
{
	setCookie('compile_files','',365);
	setCookie('compile_mode','1',365);
	//var layer = document.getElementById("sources");
	var layer = document.getElementById("code");
	$('#sources').slideUp();
	layer.innerHTML = 'loading...';
	
	$.post("loadrelevantfiles.php", {odevzdani: folder},
  function(data){
		var lay = document.getElementById("sources");
		lay.innerHTML = data;
		$('#sources').slideDown();
		$('#compl').show();
		$("#lefts").height(($('#showcode').height()));
		layer.innerHTML = '<ul><li>select files and click compile to compile</li><li>click on file to edit and save</li><li>click [edit&compile&run] to cancel your changes and restore files to version from submission</li><li>click [run] to run compiled bin, create and edit new files to redirect them to standard input</li></ul>';
  });
	  _gaq.push(['_trackEvent', 'Sources', 'Compile mode']);
}

function inex_cmpil_file(chbox)
{
	if(chbox.checked)
	{
		if(checkCookie('compile_files'))
		{
			var old = getCookie('compile_files');
			var news = old+" "+chbox.value;
			setCookie('compile_files',news,365);
		}
		else
		{
			setCookie('compile_files'," "+chbox.value,365);
		}
	}
	else
	{
		if(getCookie('compile_files').search(chbox.value) != -1)
		{
			setCookie('compile_files',getCookie('compile_files').replace(" "+chbox.value, ""),365);
		}
	}
}
var editor;
function edit(f)
{
	var lay = document.getElementById("code");
	lay.innerHTML = 'loading...';
	$.post("loadfile.php", { subor: './tmp/'+getCookie('PHPSESSIDSHA')+'/'+f, strip: '1'},
  function(data){
		lay.innerHTML = '<input type="button" id="savebutton" value="save" onclick="savetmpfile(\''+f+'\')" /><span style="float: right">powered by <a href="http://ace.ajax.org/">ace</a></span><div id="editor"></div>';
		lay.style.overflow = 'hidden';
		        editor = ace.edit("editor");
    			editor.setTheme("ace/theme/monokai");
    			editor.getSession().setMode("ace/mode/c_cpp");
    			editor.getSession().on('change', function(e) {
	    		editboxchange();
			});
			editor.setValue(data);
		$("#editor").width(($(lay).width()-4));
		$("#editor").height(($(lay).height()-25));
  });
  	  _gaq.push(['_trackEvent', 'Sources', 'Editor']);
}

function savetmpfile(file)
{
	var lay = editor.getValue();
	document.getElementById("savebutton").value = 'saving...';
	$.post("savefile.php", { subor: './tmp/'+getCookie('PHPSESSIDSHA')+'/'+file, data: lay},
  function(data){
		document.getElementById("savebutton").value = 'saved';
		if(data != '') alert(data);
  });
    _gaq.push(['_trackEvent', 'Sources', 'Editor save']);
}

function newfile()
{
	var file = document.getElementById("newfilename").value;
	$.post("savefile.php", { subor: './tmp/'+getCookie('PHPSESSIDSHA')+'/'+file, data: ''},
  function(data){
		if(data != '') alert(data);
		else
		{
			var newfile = '<li><input type="checkbox" onchange="inex_cmpil_file(this)" value="'+file+'" /> <span class="cp" onclick="edit(\''+file+'\')">'+file+'</span></li>'
			$('#sourceslist').append(newfile);
		}
  });
    _gaq.push(['_trackEvent', 'Sources', 'New file']);
}

function editboxchange()
{
	document.getElementById("savebutton").value = 'save';
}

function showfile(fi)
{
	var lay = document.getElementById("code");
	lay.innerHTML = 'loading...';
	$.post("loadfile.php", { subor: fi},
  function(data){
		lay.innerHTML = data;
  });
	  _gaq.push(['_trackEvent', 'Sources', 'Display file', fi]);
}

function closeoptions() {
	var layer = document.getElementById("showcode");
	toogle(layer);
	document.body.removeChild(layer);
	$('body').removeClass('stop-scrolling');
	
	if (getCookie('compile_mode') == '1')
	{
		$.post("cleanup.php", {},
  			function(data){
  			if(data != '') alert(data);
  			setCookie('compile_mode','0',365);
  		});
	}
}

$(document).ready(function() {

  $(window).resize(function() {
var lay = document.getElementById("code");
  $(lay).height($(window).height()-2);

var l_close = document.getElementById("cls");
var l_src = document.getElementById("sources");

$(lay).width(($(window).width()-405));

$("#editor").width(($(lay).width()-4));
$("#editor").height(($(lay).height()-25));

});
/*
$(window).onbeforeunload(function() {
alert();
  if (getCookie('compile_mode') == '1')
	{
  		$.ajax({
		url: "cleanup.php",
		type: "post",
		async: false,
		// callback handler that will be called on completion
		// which means, either on success or error
		complete: function(){
		    setCookie('compile_mode','0',365);
		}
	    });
	}
});
*/
});

function filter_sub(show)
{
	var items=new Array("nanecisto","naostro","naostro6b");
	
	$('.ode').hide();
	$('.cp').show();
	if(show == "nanecisto")
	{
		$('.green').show();
	}
	if(show == "naostro")
	{
		$('.yellow').show();
	}
	if(show == "all")
	{
		$('.green').show();
		$('.yellow').show();
	}
	
	  _gaq.push(['_trackEvent', 'Filter', 'Submission filter', show]);
}


function filter()
{
	var studf = document.getElementById("studfilter").value;
	var tutorf = document.getElementById("tutorfilter").value;
	
	if(studf == "all" && tutorf=="all")
	{
		filterNone();
		return;
	}
	if(studf == "all")
	{
		studf="";
	}
	else
	{
		studf="."+studf;
	}
	if(tutorf == "all")
	{
		tutorf="";
	}
	else
	{
		tutorf="."+tutorf;
	}
	
	$('.user').hide();
	$(studf+tutorf).slideDown();
/*
	var items=new Array("nanecisto","naostro","naostro6b");
	
	for(i = 0; i < 3; i++)
	{
		if(items[i] == show)
		{
			$('.'+items[i]).slideDown();
		}
		else
		{
			$('.'+items[i]).slideUp();
		}
	}
*/	
	$( ".user" ).promise().done(function() {
    		number_users();
  	});
  	
	_gaq.push(['_trackEvent', 'Filter', 'User filter', studf+tutorf]);
}

function filterNone()
{
	var studf = document.getElementById("studfilter").selectedIndex=0;
	var tutorf = document.getElementById("tutorfilter").selectedIndex=0;

	var items=new Array("nanecisto","naostro","naostro6b");
	
	for(i = 0; i < 3; i++)
	{
		$('.'+items[i]).show();
	}
	
	$( ".user" ).promise().done(function() {
    		number_users();
  	});
}

function getCookie(c_name)
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
}

function setCookie(c_name,value,expiredays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate()+expiredays);
document.cookie=c_name+ "=" +escape(value)+
((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

function checkCookie(c_name)
{
username=getCookie(c_name);
if (username!=null && username!="")
  {
  return true;
  }
else
  {
	return false;
  }
}

function changeTick(chbox)
{
	if(chbox.checked)
	{
		if(checkCookie('diff1'))
		{
			if(checkCookie('diff2'))
			{
				var old = document.getElementById(getCookie('diff2'));
				if(old != null) old.checked = false;
			}
			setCookie('diff2',getCookie('diff1'),365);
			setCookie('diff1',chbox.id,365);
		}
		else
		{
			setCookie('diff1',chbox.id,365);
		}
	}
	else
	{
		if(getCookie('diff1') == chbox.id)
		{
			setCookie('diff1','',365);
		}
		if(getCookie('diff2') == chbox.id)
		{
			setCookie('diff2','',365);
		}
	}
}

function showdiff(predmet, uloha) {
	if(!checkCookie('diff1') || !checkCookie('diff2'))
	{
		return;
	}

	element("showcode");
	var layer = document.getElementById("showcode");
	var text = '<div id="code">Vyberte subor</div><div id="lefts"><span id="cls" class="cp" onclick="closeoptions()">[zavriet]</span><div id="sources">loading...</div></div>';
	layer.innerHTML = text;

	$.post("loaddiffdir.php", { uloha: uloha, predmet: predmet, odevzdani: getCookie('diff1'), odevzdani2: getCookie('diff2'), diff: '1'},
  function(data){
		var lay = document.getElementById("sources");
		lay.innerHTML = 'file to diff:<br />'+data;
  });
	var layer = document.getElementById("showcode");
		toogle(layer);
$('body').addClass('stop-scrolling');
var lay = document.getElementById("code");
$(lay).height($(window).height()-2);

var l_close = document.getElementById("cls");
var l_src = document.getElementById("sources");

$(lay).width(($(window).width()-405));
$("#lefts").height(($(window).height()));

	_gaq.push(['_trackEvent', 'Diff', 'Diff layer']);
}

function showdiffout(pr, ul, filename)
{
	var lay = document.getElementById("code");
	lay.innerHTML = 'loading...';
	$.post("loaddiff.php", {predmet: pr, uloha: ul, d1: getCookie('diff1'), d2: getCookie('diff2'), subor: filename},
  function(data){
		lay.innerHTML = data;
  });
	_gaq.push(['_trackEvent', 'Diff', 'Diff difference']);
}

function get_notif()
{
var c = getCookie('lastreload');
if(intId == 0)
{
	c = '0';
}

$.post("loadlast.php", {cas: c},
  function(data){
		$('#notifbox').append(data);
		
		
		if(data != '')
		{
			$('#notifbox').scrollTo('max', 800);
			
			var id = $("#notifbox div:last-child").attr("title");
			$.post("setcookie.php", {datum: id},function(data){});
			var audioElement = document.createElement('audio');
			audioElement.setAttribute('src', 'beep.ogg');
			audioElement.play();
		}
  });
}

var intId=0;

function enable_notif(el)
{
	if(el.innerHTML == '[Enable notifications]')
	{
		$('#notifbox').show();
		get_notif();
		intId=self.setInterval("get_notif()",10000);
		el.innerHTML = '[Disable notifications]';
	}
	else
	{
		$('#notifbox').hide();
		window.clearInterval(intId);
		document.getElementById("notifbox").innerHTML = '';
		intId=0;
		el.innerHTML = '[Enable notifications]';
	}
	_gaq.push(['_trackEvent', 'Notifications', 'Notifications enabled']);
}

function update_user(user)
{
	filterNone();
	var userlayer = null;
	var userlayer = document.getElementById(user);
	var all = 0;
	if (userlayer == null)
	{
		all = 1;
	}
	$.post("loaduserlines.php", {user: user, all: all},function(data){
		if(all) $('#users').append(data);
		else $('#'+user).html(data);
		$.scrollTo("#u_"+user, 800);
		$("#"+user).slideDown('fast', function() {
    			$.scrollTo("#u_"+user, 50);
    			number_users();
  		});
  		
  		
		
	});
	
	_gaq.push(['_trackEvent', 'Notifications', 'User updated']);
}

function show_all(el)
{
	if(el.innerHTML == '[Expand all]')
	{
		showe('.odes');
		el.innerHTML = '[Collapse all]';
	}
	else
	{
		hidee('.odes');
		el.innerHTML = '[Expand all]';
	}
	
	_gaq.push(['_trackEvent', 'Display', 'Expand - Collapse']);
}

function bodyscroll()
{
	if($(document).scrollTop() > 0)
	{
		$("#space_filler").hide();
		$("#head").css( "position","relative" );
	}
	else
	{
		//$("#head").slideDown();
	}
}

function number_users()
{
	var i = 1;
	$( ".user" ).each(function( index ) {
		if($(this).css("display") == "block")
		{
			var str = i+"";
			var l = str.length;
			str="";
			for(var j = 0; j < (5-l); j++)
				str= str+"&nbsp;";
	  		$(this).children(".number").html(str+i+" ");
	  		i++;
	  	}
	});
	
	
}

function clear_cookies()
{
	setCookie('diff2', '');
	setCookie('diff1', '');
}

function panel_enabler_over()
{
	$("#head").css( "position","fixed" );
	$("#space_filler").show();
	
	_gaq.push(['_trackEvent', 'Display', 'Panel enabler used']);
}

