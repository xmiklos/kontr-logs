
<?php

class Request
{
	private $properties;
	
	function __construct()
	{
		$this->init();
	}
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
	
	function getProperty($key)
	{
		if(isset($this->properties[$key]))
		{
			return $this->properties[$key];
		}
		else
		{
			return false;
		}
	}
	
	function setProperty($key, $val)
	{
		$this->properties[$key] = $val;
	}
}

?>
