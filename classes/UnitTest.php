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
	private $has_ok = false;
        
        /**
         * States if submission has bonus tag
         * 
         * @var boolean
         */
	private $has_bonus = false;
	
	private $special_tags = false;
        
        /**
         * log entry test line
         * @var string
         */
	private $text;
	
        /**
         * Array of keys (test name) with values (number of succesfull tests)
         * 
         * @var assoc. array
         */
	public static $success_count;
        
        public static $all_count;
	
        /**
         * Constructor parses log entry line and accordingly sets class attributes
         * 
         * @param string $test_line
         */
	function __construct($test_line)
	{
		$this->text = trim($test_line);
		if(strstr($test_line, 'ok') !== false)
		{
		    $this->has_ok = true;
		}
		if(strstr($test_line, 'subtests') !== false || strstr($test_line, 'skipped') !== false)
		{
		    $this->special_tags = true;
		}
		if(strstr($test_line, 'bonus') !== false)
		{
		    $this->has_bonus = true;
		}
		
		$pos = strpos($this->text, ':');
		
		if($pos !== false && $pos != 0)
		{
		    $tags = trim(substr($this->text, $pos+1));
		    
			$key = substr($this->text, 0, $pos);
			//$tags = substr($this->text, $pos+1);
			
			//if(trim($tags) == ';') return;
			
			if($this->special_tags) return; // if no_tags then dont add this unit test to stats
			
			if(isset(UnitTest::$success_count) && array_key_exists($key, UnitTest::$success_count))
			{
				if($this->has_ok)
				{
					UnitTest::$success_count[$key]++;
                                        
				}
                                
                                UnitTest::$all_count[$key]++;
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
                                
                                UnitTest::$all_count[$key]=1;
			}
		}
		
	}
	
        /**
         * Returns css class names according to test result
         * @return string
         */
	function get_classes()
	{
		return ($this->has_ok?"blue":($this->special_tags?"white":"red"));
	}
	
	/**
         * Returns property value given that property exists
         * 
         * @param string $property
         * @return may vary
         */
	public function __get($property)
	{
		if (property_exists($this, $property))
		{
			return $this->$property;
		}
	}
        
        /**
         * Method sets property to value
         * 
         * @param type may vary $property
         * @param type $value
         */
	public function __set($property, $value)
	{
		if (property_exists($this, $property))
		{
			$this->$property = $value;
		}
		else echo "error";
	}
}

?>
