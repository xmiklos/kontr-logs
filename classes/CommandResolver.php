<?php
/**
 * CommandResolver class
 * @package
 */

require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/commands/DefaultCommand.php";

/**
 * Class provides means for determining which action 
 * shoud be executed on particular request
 */
class CommandResolver
{
        
        /**
         * Command class reflection
         * 
         * @var ReflectionClass
         */
	private static $base_cmd;
        
        /**
         * DefaultCommand instance
         * 
         * @var DefaultCommand
         */
	private static $default_cmd;
        
        /**
         * Constructor initializes attributes
         */
	function __construct()
	{
		if(!self::$base_cmd)
		{
			self::$base_cmd = new ReflectionClass("Command");
			self::$default_cmd = new DefaultCommand();
		}
	}
        
        /**
         * Method returns instance of Command class depending on request parameter 
         * 
         * @param Request $request
         * @return Command
         */
	function getCommand(Request $request)
	{
		$cmd = $request->getProperty('what');
		$allowed_commands = Config::get_array_setting("allowed_commands");
		
		if($cmd === false)
		{
			return self::$default_cmd;
		}
		
		if(!in_array($cmd, $allowed_commands))
		{
			return null;
		}
	
		$filepath = "classes/commands/{$cmd}Command.php";
		if(file_exists($filepath))
		{
			include_once($filepath);
			if (class_exists("{$cmd}Command"))
			{
				$cmd_class = new ReflectionClass("{$cmd}Command");
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
