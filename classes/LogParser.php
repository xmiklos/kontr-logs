<?php

require_once "Student.php";
require_once "Request.php";

class LogParser
{
	private $log_content;
	private $subject;
	private $task;
	private $student;

	private function get_name($line)
	{
		$parts = explode(" ", $line, 3);
		return $parts[1];
	}
	
	function __construct($log_content, Request $request) // to do, parse only stud. subs with specified parameters
	{
		$this->log_content = $log_content;
		$this->subject = $request->getProperty('subject');
		$this->task = $request->getProperty('task');
		$this->student = $request->getProperty('student');
	}
	
	function parse_as_stud()
	{
		$students = array();
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub))
			{
				$name = $this->get_name($line);

				if(array_key_exists($name, $students))
				{
					$students[$name]->add_sub($line);
				}
				else
				{
					$student = new Student($line);
					$students[$student->name] = $student;
				}
			}
		}
		
		return $students;
	}
	
	function parse_as_sub()
	{
		$subs = array();
	
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub))
			{
				$subs[] = new Submission($line);
			}
		}
		
		return $subs;
	}
}

?>
