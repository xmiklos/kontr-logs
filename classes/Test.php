<?php

class Test
{
	private $master_test;
	private $unit_test;
	private $sub_test;
	
	private $staged_files;
	private $compiled_files;
	private $staged_student_files;
	private $compiled_student_files;
	
	private $log_student;
	private $log_teacher;
	
	private $actions;
	
	private $tags;
	private $points;
	private $work_path;

	function __construct() {}

	public function __get($property)
	{
		if (property_exists($this, $property))
		{
			return $this->$property;
		}
	}

	public function __set($property, $value)
	{
		if (property_exists($this, $property))
		{
			$this->$property = $value;
		}
		else echo "error";
	}
	
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
	
	function add_action($action)
	{
		$this->action[] = $action;
	}
	
	function class_type()
	{
		$haystack = $this->work_path.$this->master_test.$this->unit_test.implode("", $this->tags);
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
	
	function class_ok()
	{
		if(count($this->tags) == 0 || (count($this->tags) == 1 && $this->tags[0] == "ok"))
		{
			return "class='square blue' title='ok'";
		}
		else
		{
			return "class='square red' title='error'";
		}
	}

}

?>
