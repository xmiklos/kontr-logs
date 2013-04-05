<?php

require_once "Submission.php";
require_once "Config.php";
require_once "File.php";

class Student
{
	public $name;
	public $tutor;
	public $uco;
	public $submissions = array();
	
	private $has_naostro = false;
	private $has_full_points = false;
	
	public static $student_tutor;
	public static $student_uco;

	function __construct($report)
	{
		$this->name = $this->add_sub($report);
		
		if(!isset(self::$student_tutor) || !isset(self::$student_tutor))
		{
			$ret = File::get_tutors();
			self::$student_tutor = $ret['tutor'];
			self::$student_uco = $ret['uco'];
		}
		
		if(array_key_exists($this->name, self::$student_tutor))
		{
			$this->tutor = self::$student_tutor[$this->name];
			$this->uco = self::$student_uco[$this->name];
		}
	}

	function add_sub($report)	
	{
		$sub = new Submission($report);
		$this->submissions[] = $sub;
		
		if($sub->test_type == "teacher") $this->has_naostro = true;
		if(strstr($sub->summary, "points=6") !== false) $this->has_full_points = true;
		
		return $sub->name;
	}

	function count_subs()
	{
		$count = array(0, 0);
		foreach($this->submissions as $sub)
		{
			if($sub->test_type == "teacher")
				$count[0]++;
			else
				$count[1]++;
		}
		return $count;
	}
	
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
		
		if(isset(self::$student_tutor) && array_key_exists($this->name, self::$student_tutor))
		{
			$cls.=$this->tutor;
		}
		
		return $cls;
	}
	
}

?>
