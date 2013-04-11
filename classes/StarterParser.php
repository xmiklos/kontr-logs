<?php

require_once "SystemLogsParser.php";

class StarterParser extends SystemLogsParser
{

	function __construct($log_content)
	{
		parent::__construct($log_content);
	}

	function parse()
	{
		$log_entries = array();
		$lines = explode("\n", $this->log_content);
		
		foreach($lines as $line)
		{
			if($line == "") continue;
			$entry = array();
			$entry[] = $line;
			$log_entries[] = $entry;
		}
		
		return $log_entries;
	}

}


?>
