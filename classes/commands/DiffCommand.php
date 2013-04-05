<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/DiffBuilder.php";
require_once "classes/File.php";

class DiffCommand extends Command
{
	function doExecute(Request $request)
	{
		$diff = new DiffBuilder($request);
		$diff->start();
		$diff->incl("diff.php");
		$diff->render();
	}
}

?>
