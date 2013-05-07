<?php
class Config
{
	private static $conf;
	
	private function __construct() {}
	
	static function init($config_file = "./config.ini")
	{
		if(!isset(self::$conf))
		{
			self::$conf = parse_ini_file($config_file);
		}
	}
	
	static function get_setting($key)
	{
		return (array_key_exists($key, self::$conf)?self::$conf[$key]:false);
	}
	
	static function split($str, $delim = ",")
	{
		return explode($delim, $str);
	}
}

?>
