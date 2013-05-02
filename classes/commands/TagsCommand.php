<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/LogBuilder.php";


class TagsCommand extends Command
{
	function doExecute(Request $request)
	{
		$log = new LogBuilder($request);
		
		echo "<option value='none'>none</option>";
		foreach($log->parser->parse_as_tags() as $tag)
		{
			echo "<option value='{$tag}'>{$tag}</option>";
		}
		
		
	}
}

?>
