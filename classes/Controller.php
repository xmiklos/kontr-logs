
<?php
require_once "classes/Config.php";
require_once "classes/Request.php";
require_once "classes/CommandResolver.php";
require_once "classes/Auth.php";

class Controller
{

	private $config;
	private function __construct() {}
	
	static function run()
	{
		$instance = new Controller();
		$instance->init();
		$instance->handle_request();
	}
	
	function init()
	{
		$this->config = Config::instance();
		$this->config->init();
		
		//session_start();
		
		$auth_class = $this->config->get_setting("authenticate");
		
		if($auth_class)
		{
			require_once "classes/{$auth_class}.php";
			$ref = new ReflectionClass($auth_class);
			$auth = $ref->newInstance();
			$auth->login();
		}
		
		session_start();
	}
	
	function handle_request()
	{
		$req = new Request();
		$res = new CommandResolver();
		$cmd = $res->getCommand($req);
		$cmd->execute($req);
	}

}

?>
