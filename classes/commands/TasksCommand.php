
<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/IndexBuilder.php";

class TasksCommand extends Command
{
	function doExecute(Request $request)
	{
		$index = new IndexBuilder($request);
		$index->start();
		$index->option_tasks();
		$index->render();
	}
	
}

?>
