<?php
class Config
{
	private static $conf;
	private static $instance;
	
	private function __construct() {}
	
	static function instance()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	function init()
	{
		if(!isset(self::$conf))
		{
			self::$conf = parse_ini_file("./config.ini");
		}
	}
	
	function get_setting($key)
	{
		return (array_key_exists($key, self::$conf)?self::$conf[$key]:false);
	}
}

?>
