<?php

require_once "SystemLogsParser.php";

class KontrPlParser extends SystemLogsParser
{

	function __construct($log_content)
	{
		parent::__construct($log_content);
	}
	
	private function entry_has_errors($lines)
	{
		$search = array("[KONTR]", "<user>", "Checked out", "A    ", "Updated to", "At revision", "U    ", "/home/", "D    ", " U   ");
		
		foreach($lines as $line)
		{
			$errors = true;
			foreach($search as $needle)
			{
				$ret = strpos($line, $needle);
				if($ret !== false && $ret == 0)
				{
					$errors = false;
					break;
				}
			}
			if($errors) return true;
		}
		
		return false;
	}
	
	function parse()
	{
		$log_entries = array();
		$lines = explode("\n", $this->log_content);
		
		$i = 0;
		$error_entry=array();

		while($i < count($lines))
		{
			$last_space = strrpos($lines[$i], " ");
			
			if($last_space === false)
			{
				$error_entry[] = $lines[$i];
				$i++;
				continue;
			}
			
			
			$stage_dir = substr($lines[$i], 0, $last_space);
			$line_count = substr($lines[$i], $last_space+1);
			
			if($line_count === false || !is_numeric($line_count))
			{
				$error_entry[] = "ERROR: ".$lines[$i];
				$i++;
				continue;
			}
			
			if(count($error_entry) > 0)
			{
				$log_entries[] = $error_entry;
				$error_entry=array();
			}
			
			$entry=array();
			
			$j = ($i+$line_count);
			$entry[] = $stage_dir;
			for(++$i; $i <= $j; $i++)
			{
				$entry[] = $lines[$i];
			}
			
			if($this->entry_has_errors($entry))
			{
				$log_entries[] = $entry;
			}
		}
		
		return $log_entries;
	}

}


?>
