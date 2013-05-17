<?php
/**
 * PageBuilder class
 * @package
 */

require_once "classes/File.php";

/**
 * Class provides means for including html templates and output buffering
 */
class PageBuilder
{
        /**
         * request instance
         * @var Request
         */
	private $request;

        /**
         * Constructor initializes request attribute
         * 
         * @param Request $request
         */
	function __construct($request)
	{
		$this->request = $request;
	}
	
        /**
         * Method starts output buffering
         */
	function start()
	{
		ob_start();
	}
	
        /**
         * Method for including files to class
         * simply to allow them to call class methods
         * and mainly methods of subclasses
         * 
         * @param string $filename filename to iclude
         */
	function incl($filename)
	{
		include_once $filename;
	}
        
        /**
         * Method ends output buffering and echoes output out
         */
	final function render()
	{
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}
	
        /**
         * Method returns request object
         * 
         * @return Request
         */
	function get_request()
	{
		return $this->request;
	}
}

?>
