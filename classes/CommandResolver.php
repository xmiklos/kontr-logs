
<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/DefaultCommand.php";

class CommandResolver
{

	private static $base_cmd;
	private static $default_cmd;

	function __construct()
	{
		if(!self::$base_cmd)
		{
			self::$base_cmd = new ReflectionClass("Command");
			self::$default_cmd = new DefaultCommand();
		}
	}

	function getCommand(Request $request)
	{
		$cmd = $request->getProperty('what');
		
		if(!$cmd)
		{
			return self::$default_cmd;
		}
		
		$filepath = "classes/{$cmd}.php";
		if(file_exists($filepath))
		{
			require_once($filepath);
			if (class_exists($cmd))
			{
				$cmd_class = new ReflectionClass($cmd);
				if ($cmd_class->isSubClassOf(self::$base_cmd))
				{
					return $cmd_class->newInstance();
				} 
			}
		}

		return clone self::$default_cmd;
	}
}

?>
