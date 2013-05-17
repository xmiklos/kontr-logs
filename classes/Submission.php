<?php
/**
 * Submission class
 * @package
 */

require "UnitTest.php";

/**
 * Class represents one student submission
 */
class Submission
{
        /**
         * name of student that owns submission
         * @var string
         */
	private $name;
        
        /**
         * submission folder identification
         * @var string
         */
	private $folder;
        
        /**
         * date in string format
         * @var string
         */
	private $date;
        
        /**
         * time since unix epoch
         * @var integer
         */
	private $unix_date;
        
        /**
         * one of: naostro, nenecisto
         * @var string
         */
	private $test_type;
        
        /**
         * Tag summary (tag union)
         * @var string
         */
	private $summary;
        
        /**
         * list of tags
         * @var string
         */
	private $tags;
        
        /**
         * Indicates if submissions has tag ok
         * @var boolean
         */
	private $sub_ok = true;
        
        /**
         * Indicates that submission is ok, but bonus has error
         * @var boolean
         */
	private $bonus_nok = false;
        
        /**
         * Array of UnitTest objects
         * @var array
         */
	private $unit_tests = array();
        
        /**
         * SVN revision of submission
         * @var string
         */
	private $revision;
        
        /**
         * Name of student to whom submission belongs to
         * @var string
         */
	private $resubmitted_name;
        
        /**
         * Indicates that submission is new
         * @var boolean
         */
	private $is_new = false;
	
        /**
         * Constructor parses submission string and initializes class attributes
         * 
         * @param string $report log file entry content
         */
	function __construct($report)
	{
		$parts = explode(" ", $report);
		$this->resubmitted_name = substr($parts[6], 0, -1);
		
		if($this->resubmitted_name == "0")
		{
			$this->folder = $parts[1]."_".$parts[0];
		}
		else
		{
			$this->folder = $this->resubmitted_name."_".$parts[0];
		}
		
		$this->date = $this->get_date($parts[0]);
		$this->unix_date = $this->get_unix_time($parts[0]); 
		$this->name = $parts[1];
		$this->revision = $parts[2];
		
		if($parts[5] == "teacher")
		{
			$this->test_type = "naostro";
		}
		else
		{
			$this->test_type = "nanecisto";
		}
		
		if(array_key_exists('last_update' ,$_COOKIE) && $this->test_type == "naostro")
		{
			$time = $_COOKIE['last_update'];
			
			if($this->unix_date >= $time)
			{
				$this->is_new = true;
			}
		}
		
		$parts = explode("#", $report);
		$sum = explode(":", $parts[0]);
		$this->summary = trim($sum[1]);
		
		$semi_pos = strpos($this->summary, ';');
		$this->tags = substr($this->summary, 0, $semi_pos);
		
		for($i = 1; $i < count($parts); $i++)
		{
			$u_test = new UnitTest($parts[$i]);
			$this->unit_tests[] = $u_test;
			if($this->sub_ok)
			{
				if(!$u_test->has_ok && $u_test->has_bonus)
				{
					$this->bonus_nok = true;
				}
				else
				{
					$this->sub_ok = $u_test->has_ok;
				}
			}
		}
	}
	
        /**
         * Method parses date from string and returns human readable format
         * 
         * @param string $date
         * @return string
         */
	function get_date($date)
	{
		$p = explode("_", $date);
		$rok = $p[0];
		$mesiac = substr($p[1], 0, 2);
		$den = substr($p[1], -2);

		$hod = substr($p[2], 0, 2);
		$min = substr($p[2], 2, 2);
		$sec = substr($p[2], -2);

		return $den.".".$mesiac.".".$rok." - ".$hod.":".$min.":".$sec;
	}
	
        /**
         * Method parses date in string and returns unix epoch time
         * 
         * @param string $date
         * @return integer unix time
         */
	function get_unix_time($date)
	{
		$p = explode("_", $date);
		$rok = $p[0];
		$mesiac = substr($p[1], 0, 2);
		$den = substr($p[1], -2);

		$hod = substr($p[2], 0, 2);
		$min = substr($p[2], 2, 2);
		$sec = substr($p[2], -2);

		return mktime($hod, $min, $sec, $mesiac, $den, $rok);
	}
	
        /**
         * Method returns css classes depending on submission results
         * 
         * @return string
         */
	function get_summary_classes()
	{
		
		if($this->sub_ok)
		{
			if($this->bonus_nok)
			{
				return "purple";
			}
			else
			{
				return "blue";
			}
		}
		else 
		{
			return "red";
		}
	}
	
        /**
         * Method returns css classes depending on submission type
         * 
         * @return string
         */
	function get_classes()
	{
		return "ode ".($this->test_type=="naostro"?"yellow":"green");
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
