//var KLogs = window.Klogs || {};

KLogs.Editor = (function(){
    var editor;
    
    return {
        init: function()
        {
            editor = ace.edit("editor");
            //editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/c_cpp");
            editor.getSession().on('change', KLogs.Editor.change);
            $("body").on('click', '.editor_save', KLogs.Editor.save);
        },
        change: function()
        {
            $('.editor_output').html('');
        },
        save: function()
        {
            var content = editor.getValue();
            var task = $('#editor').data('task');
            var subject = $('#editor').data('subject');
            var folder = $('#editor').data('folder');
            var key = $('#editor').data('key');
            $.post("index.php", {what: 'Editor', task: task, subject: subject, 
                                sub_folder: folder, key: key, save: "save", data: content},
                function(data)
		{
			$('.editor_output').html(data);
                }).fail(function(jqXHR, textStatus, errorThrown){
	  		$('.editor_output').html(textStatus + " - " + errorThrown);
	  	});
        }
        
        
    };
})();

$(document).ready(function() {
    KLogs.Editor.init();
    KLogs.Loading.init();
    
    $(document).on({
    ajaxStart: function(e, h) {
    	KLogs.Loading.show();
    },
    ajaxStop: function() {
    	KLogs.Loading.hide();
    }
});
});