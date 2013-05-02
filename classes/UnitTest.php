<?php

class UnitTest
{
	public $has_ok = false;
	public $has_bonus = false;
	public $text;
	
	public static $success_count;
	
	function __construct($test_line)
	{
		$this->text = trim($test_line);
		if(strstr($test_line, 'ok') !== false) $this->has_ok = true;
		if(strstr($test_line, 'bonus') !== false) $this->has_bonus = true;
		
		$pos = strpos($this->text, ':');
		
		if($pos !== false && $pos != 0)
		{
			$key = substr($this->text, 0, $pos);
			//$tags = substr($this->text, $pos+1);
			
			//if(trim($tags) == ';') return;
			
			if(isset(UnitTest::$success_count) && array_key_exists($key, UnitTest::$success_count))
			{
				if($this->has_ok)
				{
					UnitTest::$success_count[$key]++;
				}
			}
			else
			{
				if($this->has_ok)
				{
					UnitTest::$success_count[$key]=1;
				}
				else
				{
					UnitTest::$success_count[$key]=0;
				}
			}
		}
		
	}
	
	function get_classes()
	{
		return ($this->has_ok?"blue":"red");
	}
}

?>
