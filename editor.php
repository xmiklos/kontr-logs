<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/editor.css" type="text/css" />
	<link rel="stylesheet" href="css/code_highlight.css" type="text/css" />
	<link rel="stylesheet" href="css/jquery-ui-1.10.2.custom.css" type="text/css" />
	<script src="js/jquery-1.9.1.min.js" type="text/javascript" ></script>
	<script src="js/jquery.scrollTo-1.4.3.1-min.js" type="text/javascript" ></script>
	<script src="js/jquery-ui-1.10.2.custom.min.js" type="text/javascript" ></script>
	<script src="js/jquery.sortElements.js" type="text/javascript" ></script>
	<script src="js/ace.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/KLogs.js" type="text/javascript" ></script>
	<script src="js/editor.js" type="text/javascript" ></script>

<title>_logs_ - <?php $this->title(); ?></title>
</head>
<body >
    <button class="editor_save">Save</button>
    <span class="editor_output"></span>
    <div id="editor" <?php $this->data(); ?>><?php $this->source(); ?></div>
    
</body>
</html>

<?php

?>
