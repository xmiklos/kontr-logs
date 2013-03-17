<?php

class UnitTest
{
	public $has_ok = false;
	public $has_bonus = false;
	public $text;
	
	function __construct($test_line)
	{
		$this->text = trim($test_line);
		if(strstr($test_line, 'ok') !== false) $this->has_ok = true;
		if(strstr($test_line, 'bonus') !== false) $this->has_bonus = true;
		
	}
	
	function get_classes()
	{
		return ($this->has_ok?"blue":"red");
	}
}

?>
