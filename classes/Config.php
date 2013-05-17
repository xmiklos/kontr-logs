<?php
/**
 * Config class
 * @package
 */

/**
 * Class loads aplication configuration and provides methods
 * for gathering requested entries
 */
class Config
{
        /**
         * Array holds parsed configuration
         * @var array
         */
	private static $conf;
	
        /**
         * Private constructor
         */
	private function __construct() {}
	
        /**
         * Method loads confiruration into memory
         * 
         * @param string $config_file
         */
	static function init($config_file = "./config.ini")
	{
		if(!isset(self::$conf))
		{
			self::$conf = parse_ini_file($config_file);
		}
	}
	
        /**
         * Method returns configuration value of specified key
         * 
         * @param string $key
         * @return string
         */
	static function get_setting($key)
	{
		return (array_key_exists($key, self::$conf)?self::$conf[$key]:false);
	}
	
        /**
         * Method gets value of configuration setting of key as array
         * 
         * @param string $key
         * @param string $delim character to split value on
         * @return boolean on failure, array on success
         */
	static function get_array_setting($key, $delim = ",")
	{
		if(!array_key_exists($key, self::$conf))
		{
			return false;
		}
		
		return explode($delim, self::$conf[$key]);
	}
}

?>
