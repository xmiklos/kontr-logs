
<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/LogBuilder.php";


class LogsCommand extends Command
{
	function doExecute(Request $request)
	{
		$log = new LogBuilder($request);
		$log->start();
		$log->show();
		$log->render();
	}
}

?>
