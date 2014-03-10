<?php
/**
 * Test class
 * @package
 */

/**
 * Class represents one unit or subtest in context of detailed log
 */
class Test
{
        /**
         * name of master test
         * @var string
         */
	private $master_test;
        
        /**
         * name of unit test
         * @var string
         */
	private $unit_test;
        
        /**
         * name of sub test if any
         * @var string
         */
	private $sub_test;
	
        /**
         * array of staged files
         * @var array
         */
	private $staged_files;
        
        /**
         * array of compiled files
         * @var array
         */
	private $compiled_files;
        
        /**
         * array of student files
         * @var array
         */
	private $staged_student_files;
        
        /**
         * array of student compiled files
         * @var array
         */
	private $compiled_student_files;
	
        /**
         * content of student log
         * @var string
         */
	private $log_student;
        
        /**
         * content of student log
         * @var string
         */
	private $log_teacher;
	
        /**
         * files added to teacher log
         * @var array
         */
	private $teacher_log_files;
        
        /**
         * files added to student log
         * @var array
         */
	private $student_log_files;
	
        /**
         * actions list
         * @var array
         */
	private $actions;
	
        /**
         * list of tags
         * @var array
         */
	private $tags;
        
        /**
         * Points given
         * @var integer
         */
	private $points;
        
        /**
         * test work path
         * @var string
         */
	private $work_path;
        
        /**
         * Constructor
         */
	function __construct() {}
        
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
	
        /**
         * Method for adding files to file classes
         * 
         * @param array $property property name
         * @param array $files files to add
         */
	function add_files($property, $files)
	{
		if(property_exists($this, $property))
		{
			if(!isset($this->$property))
			{
				$this->$property = array();
			}
		
			$this->$property = array_merge($this->$property, $files);
		}
	}
	
        /**
         * Method for adding test log content.
         * 
         * @param string $property property to add to
         * @param string $content content to add
         */
	function add_log($property, $content)
	{
		if(property_exists($this, $property))
		{
			if(!isset($this->$property))
			{
				$this->$property = "";
			}
		
			$this->$property .= $content;
		}
	}
	
        /**
         * Method for adding files added to test log
         * 
         * @param string $property
         * @param string $content 
         */
	function add_log_files($property, $content)
	{
		if(property_exists($this, $property))
		{
			if(!isset($this->$property))
			{
				$this->$property = array();
			}
			
			array_push($this->$property, $content);
		}
	}
	
        /**
         * Adds action to action array property
         * 
         * @param $action
         */
	function add_action($action)
	{
		$this->action[] = $action;
	}
	
        /**
         * Method returns css classes according to test type and bonus results
         * 
         * @return string
         */
	function class_type()
	{
		$haystack = $this->work_path.$this->master_test; //.$this->unit_test.implode("", $this->tags);
		if(strpos($haystack, "bonus") !== false)
		{
			return "class='square purple' title='bonus'";
		}
		else if(strpos($haystack, "naostro") !== false)
		{
			return "class='square yellow' title='naostro'";
		}
		else
		{
			return "class='square green' title='nanecisto'";
		}
	}
	
        /**
         * Method returns css classes according to test results
         * 
         * @return string
         */
	function class_ok()
	{
	    $ok = false;
	    
	    if(count($this->tags) == 1 && $this->tags[0] == "ok")
	    {
	        $ok = true;
	    }
	    elseif(count($this->tags) > 1)
	    {
	        foreach($this->tags as $tag)
	        {
	            if($tag == "ok")
	            {
	                $ok = true;
	                break;
	            }
	        }
	    }
	    
		if($ok)
		{
			return "class='square blue' title='ok'";
		}
		elseif(count($this->tags) == 1 && ($this->tags[0] == "subtests" || $this->tags[0] == "skipped"))
		{
		    return "class='square white' title='{$this->tags[0]}'";
		}
		else
		{
			return "class='square red' title='error'";
		}
	}
        
        function get_action($action)
        {
            $actions = $this->actions;

            foreach($actions as $act)
            {
                if(strpos($act['name'], $action) !== false)
                {
                    return $act;
                }
            }

            return false;
        }

}

?>
