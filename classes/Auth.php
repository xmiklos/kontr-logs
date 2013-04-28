<?php

abstract class Auth
{
	private static $username;
	
	function __construct() {}
	
	static function get_username()
	{
		return self::$username;
	}
	/*
	static function logged()
	{
		return (isset($_SESSION['logged'])?$_SESSION['logged']:false);
	}
	*/
	protected function set_username($name)
	{
		self::$username = $name;
	}
	
	abstract function login();
	
}

?>
