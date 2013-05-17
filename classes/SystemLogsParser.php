<?php
/**
 * SystemLogsarser class
 * @package
 */

/**
 * Class provides interface for implementing parser classes for different kontr logs
 */
abstract class SystemLogsParser
{
        /**
         * Log content to parse
         * 
         * @var string
         */
	protected $log_content;
        
        /**
         * Constructor assigns log content as array to log_content attribute
         * 
         * @param string $log_content
         */
	function __construct($log_content)
	{
		$this->log_content = $log_content;
	}
	
        /**
         * Method for implementing parsing in subclasses
         */
	abstract function parse();

}


?>
