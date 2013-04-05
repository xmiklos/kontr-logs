<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/IndexBuilder.php";
require_once "LogsCommand.php";

class NotifCommand extends Command
{
	function doExecute(Request $request)
	{
		$log = new LogBuilder($request);
		$log->start();
		$log->show_notif();
		$log->render();
	}
}

?>
