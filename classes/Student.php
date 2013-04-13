<?php

require_once "Submission.php";
require_once "StudentInfo.php";
require_once "TeacherInfo.php";
require_once "Config.php";
require_once "File.php";

class Student
{
	public $name;
	public $tutor = "No tutor";
	public $uco = false;
	public $full_name = "Unknown";
	public $submissions = array();
	public $has_new_subs = false;
	public $is_special = false;
	
	private $has_naostro = false;
	private $has_full_points = false;

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
