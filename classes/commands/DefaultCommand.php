<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/IndexBuilder.php";
require_once "LogsCommand.php";

class DefaultCommand extends Command
{
	function doExecute(Request $request)
	{
		setcookie("last_update", time());
		$index = new IndexBuilder($request);

		$index->start();
		$index->incl("head.php");
		$index->incl("panel.php");
		
		$log = new LogsCommand();
		$log->execute($request);
		
		$index->incl("footer.php");

		$index->render();
		
	}
	
}

?>
