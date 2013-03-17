<?php

require "UnitTest.php";

class Submission
{
	public $name;
	public $folder;
	public $date;
	public $test_type;
	public $summary;
	public $sub_ok = true;
	public $bonus_nok = false;
	public $unit_tests = array();
	
	function __construct($report)
	{
		$parts = explode(" ", $report);
		$this->folder = $parts[1]."_".$parts[0];
		$this->date = $this->get_date($parts[0]); 
		$this->name = $parts[1];
		$this->test_type = $parts[5]; // student or teacher
		
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
		return "ode ".($this->test_type=="teacher"?"yellow":"green");
	}

}

?>
