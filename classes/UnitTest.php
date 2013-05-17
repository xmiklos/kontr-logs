<?php
/**
 * UnitTest class
 * @package
 */

/**
 * Class represents one unit test or subtest in context of submission log
 */
class UnitTest
{
        /**
         * States if submission has ok tag
         * 
         * @var boolean
         */
	public $has_ok = false;
        
        /**
         * States if submission has bonus tag
         * 
         * @var boolean
         */
	public $has_bonus = false;
        
        /**
         * log entry test line
         * @var string
         */
	public $text;
	
        /**
         * Array of keys (test name) with values (number of succesfull tests)
         * 
         * @var assoc. array
         */
	public static $success_count;
	
        /**
         * Constructor parses log entry line and accordingly sets class attributes
         * 
         * @param string $test_line
         */
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
	
        /**
         * Returns css class names according to test result
         * @return string
         */
	function get_classes()
	{
		return ($this->has_ok?"blue":"red");
	}
}

?>
