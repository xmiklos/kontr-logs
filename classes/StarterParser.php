<?php
/**
 * StarterParser class
 * @package
 */

require_once "SystemLogsParser.php";

/**
 * Class for parsing both Kontr starter logs
 */
class StarterParser extends SystemLogsParser
{
        
        /**
         * Constructor initializes log_content attribute of super class
         * @param string $log_content
         */
	function __construct($log_content)
	{
		parent::__construct($log_content);
	}

        /**
         * Method simply parses log lines and return them as array
         * @return array
         */
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
