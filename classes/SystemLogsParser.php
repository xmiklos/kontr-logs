
<?php

abstract class SystemLogsParser
{
	protected $log_content;

	function __construct($log_content)
	{
		$this->log_content = $log_content;
	}
	
	abstract function parse();

}


?>
