<?php
/**
 * Request class
 * @package
 */

/**
 * Request class simplyfies work with $_REQUEST global
 */
class Request
{
        /**
         * Holds request properties
         * @var array
         */
	private $properties;
	
        /**
         * Constructor calls init method
         */
	function __construct()
	{
		$this->init();
	}
        
        /**
         * Method initializes properties attribute 
         */
	function init()
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			$this->properties = $_REQUEST;
			return;
		}
		
		foreach($_SERVER['argv'] as $arg)
		{
			if(strpos($arg,'='))
			{
				list($key, $val)=explode("=", $arg);
				$this->setProperty($key, $val);
			}
		}
	}
	
        /**
         * Method gets value of given $key
         * 
         * @param string $key
         * @return value of given key or false on key not existent
         */
	function getProperty($key)
	{
		if(array_key_exists($key, $this->properties))
		{
			return $this->properties[$key];
		}
		else
		{
			return false;
		}
	}
	
        /**
         * Method for manual change of request property
         * 
         * @param string $key
         * @param string $val
         */
	function setProperty($key, $val)
	{
		$this->properties[$key] = $val;
	}
}

?>
