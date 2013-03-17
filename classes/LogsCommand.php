
<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/LogDisplay.php";


class LogsCommand extends Command
{
	function doExecute(Request $request)
	{
		//$_SESSION['task'] = $request->getProperty('task');
		//$_SESSION['subject'] = $request->getProperty('subject');
		$display = new LogDisplay();
		$display->showHTML();
	}
}

?>
