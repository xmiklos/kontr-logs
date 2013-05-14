<?php

abstract class Auth
{
	private static $username;
	private static $logged = false;
	
	final function __construct() {}
	
	static function get_username()
	{
		return self::$username;
	}
	
	static function try_authenticate()
	{
		$auth_class = Config::get_setting("authentication");
		
		if($auth_class)
		{
			require_once "classes/{$auth_class}.php";
			$ref = new ReflectionClass($auth_class);
			$auth = $ref->newInstance();
			$auth->login();
			
			if($auth->is_logged() === false)
			{
				echo "Unauthorized access!";
				exit;
			}
		}
	}
	
	protected function set_username($name)
	{
		self::$username = $name;
	}
	
	protected function set_logged($state)
	{
		self::$logged = $state;
	}
	
	static function is_logged()
	{
		return self::$logged;
	}
	
	abstract function login();
	
}

?>
