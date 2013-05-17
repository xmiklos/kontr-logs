<?php
/**
 * Controller class
 * @package
 */

require_once "classes/Config.php";
require_once "classes/Request.php";
require_once "classes/CommandResolver.php";
require_once "classes/Auth.php";

/**
 * Base class of the application,
 * provides entry point
 */
class Controller
{
        
        /**
         * Private constructor
         */
	private function __construct() {}
	
        /**
         * application entry point
         */
	static function run()
	{
		$instance = new Controller();
		$instance->init();
		$instance->handle_request();
	}
	
        /**
         * base initialization method
         */
	function init()
	{
		Config::init();
		Auth::try_authenticate();
	}
	
        /**
         * method executes command depending on client request
         */
	function handle_request()
	{
		$req = new Request();
		$res = new CommandResolver();
		$cmd = $res->getCommand($req);
		$cmd->execute($req);
	}

}

?>
