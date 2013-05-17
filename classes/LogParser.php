<?php
/**
 * LogParser class
 * @package
 */

require_once "Student.php";
require_once "Request.php";

/**
 * Class LogParser provides methods for parsing report.log file
 */
class LogParser
{
        /**
         * content of log file to parse
         * @var string
         */
	private $log_content;
        
        /**
         * subject
         * @var string
         */
	private $subject;
        
        /**
         * task
         * @var string
         */
	private $task;
        
        /**
         * Student login
         * @var string
         */
	private $student;
        
        /**
         * Tutor login
         * @var string
         */
	private $tutor;
        
        /**
         * Tutors list, keys are student logins, values tutor logins
         * @var array
         */
	private $tutors;
        
        /**
         * Method gets student login from submission line
         * 
         * @param string $line
         * @return string
         */
	private function get_name($line)
	{
		$parts = explode(" ", $line, 3);
		return $parts[1];
	}
	
        /**
         * Constructor initializes attributes from request parameters
         * 
         * @param string $log_content
         * @param Request $request
         */
	function __construct($log_content, Request $request)
	{
		$this->log_content = $log_content;
		$this->subject = $request->getProperty('subject');
		$this->task = $request->getProperty('task');
		$this->student = $request->getProperty('student');
		$this->tutor = $request->getProperty('tutor');
	}
	
        /**
         * Main method for parsing student submissions and assigning them to students
         * 
         * @return array of \Student s
         */
	function parse_as_stud()
	{
		$students = array();
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		if($this->student)
		{
			$stud_login = $this->student;
		}
		else
		{
			$stud_login = "";
		}
		
		$tutor_filter = false;
		if($this->tutor && $this->tutor != "")
		{
			$tutor_filter = true;
			$ret = File::get_student_info();
			$this->tutors = $ret['tutor'];
		}
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub) && (!$this->student || strstr($line, $stud_login." "))) // added space to student filename to avoid nasty bug
			{
				$name = $this->get_name($line);
				
				if($tutor_filter)
				{
					if(!array_key_exists($name, $this->tutors))
					{
						continue;
					}
					else if($this->tutor!=".{$this->tutors[$name]}")
					{
						continue;
					}
				}

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
	
        /**
         * Method parses log file and returns array of Submission objects
         * 
         * @return array of \Submission s
         */
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
	
        /**
         * Method parses submissions and returns all tags present
         * 
         * @return array
         */
	function parse_as_tags()
	{
		$tags = array();
	
		$lines = explode("\n", $this->log_content);
		
		$sub = $this->subject." ".$this->task;
		
		foreach($lines as $line)
		{
			if(strstr($line, $sub))
			{
				$colon = strpos($line, ':');
				$semi = strpos($line, ';');
				$len = $semi - $colon;
				$tags_str = trim(substr($line, $colon+1, $len-1));
				$tags_array = explode(" ", $tags_str);
				$tags = array_merge($tags, $tags_array);
			}
		}
		
		return array_unique($tags);
	}
}

?>
