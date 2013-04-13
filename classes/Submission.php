<?php

require "UnitTest.php";

class Submission
{
	public $name;
	public $folder;
	public $date;
	public $unix_date;
	public $test_type;
	public $summary;
	public $sub_ok = true;
	public $bonus_nok = false;
	public $unit_tests = array();
	public $revision;
	public $resubmitted_name;
	public $is_new = false;
	
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
	
	function get_classes()
	{
		return "ode ".($this->test_type=="naostro"?"yellow":"green");
	}

}

?>
