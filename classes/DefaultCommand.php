
<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/IndexBuilder.php";
require_once "classes/LogDisplay.php";

class DefaultCommand extends Command
{
	function doExecute(Request $request)
	{
		
		$index = new IndexBuilder();
		$index->start($request);
		$index->incl("head.php");
		$index->incl("panel.php");
		
		$d = new LogDisplay($request);
		$d->show();
		
		$index->incl("footer.php");
		
		$index->render();
		
	}
	
}

?>
