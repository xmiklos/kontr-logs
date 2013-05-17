<?php
/**
 * Student class
 * @package
 */

require_once "Submission.php";
require_once "StudentInfo.php";
require_once "TeacherInfo.php";
require_once "Config.php";
require_once "File.php";

/**
 * Class represents Student with his submissions
 */
class Student
{
        /**
         * student name
         * 
         * @var string
         */
	public $name;
        
        /**
         * Student's tutor
         * @var string
         */
	public $tutor = "No tutor";
        
        /**
         * Students uco
         * @var string
         */
	public $uco = false;
        
        /**
         * Students full name
         * @var string
         */
	public $full_name = "Unknown";
        
        /**
         * List of students submissions
         * 
         * @var array of Submission s
         */
	public $submissions = array();
        
        /**
         * Indicates that student has new submissions
         * @var boolean
         */
	public $has_new_subs = false;
        
        /**
         * Indicates that student is in fact special user
         * @var boolean
         */
	public $is_special = false;
	
        /**
         * Indicates if student submitted at least one naostro submission
         * @var boolean
         */
	private $has_naostro = false;
        
        /**
         * Indicates that student has full points
         * @var boolean
         */
	private $has_full_points = false;
        
        /**
         * Creates student by adding first submission
         * Initializes static properties
         * 
         * @param string $report
         */
	function __construct($report)
	{
		$this->name = $this->add_sub($report);

		if(StudentInfo::exists($this->name))
		{
			$this->tutor = StudentInfo::get($this->name, 'tutor');
			$this->uco = StudentInfo::get($this->name, 'uco');
			$this->full_name = StudentInfo::get($this->name, 'full_name');
		}
		else if(TeacherInfo::exists($this->name))
		{
			$this->tutor = TeacherInfo::get($this->name, 'tutor');
			$this->uco = TeacherInfo::get($this->name, 'uco');
			$this->full_name = TeacherInfo::get($this->name, 'full_name');
			$this->is_special = true;
		}
	}
        
        /**
         * Method creates student submission from log entry line
         * 
         * @param string $report
         * @return string student name
         */
	function add_sub($report)	
	{
		$sub = new Submission($report);
		$this->submissions[] = $sub;
		
		if($sub->is_new)
		{
			$this->has_new_subs = true;
		}
		
		if($sub->test_type == "naostro")
		{
			$this->has_naostro = true;
		}
		
		if(strstr($sub->summary, "points=6") !== false)
		{
			$this->has_full_points = true;
		}
		
		return $sub->name;
	}
        
        /**
         * Method returns array with two indexes 0 and 1 that contains
         * number of each test type
         * 
         * @return int
         */
	function count_subs()
	{
		$count = array(0, 0);
		foreach($this->submissions as $sub)
		{
			if($sub->test_type == "naostro")
				$count[0]++;
			else
				$count[1]++;
		}
		return $count;
	}
	
        /**
         * Method returns css classes depending on submission type and results
         * 
         * @return string
         */
	function get_classes()
	{
		$cls="user ";
		
		if($this->has_naostro)
		{
			if($this->has_full_points)
			{
				$cls.="naostro naostro6b ";
			}
			else
			{
				$cls.="naostro naostroe ";
			}
		}
		else
		{
			$cls.="nanecisto ";
		}
		
		if(StudentInfo::get($this->name, 'tutor') != "")
		{
			$cls.=$this->tutor;
		}
		
		return $cls;
	}
	
}

?>
