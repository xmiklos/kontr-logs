<?php
/**
 * Command class
 * @package
 */

/**
 * Provides interface for implementing commands
 */
abstract class Command
{
        /**
         * final constructor, cannot be overriden
         */
	final function __construct() {}
	
        /**
         * Calls doExecute method
         * @param Request $request
         */
	function execute(Request $request)
	{
		$this->doExecute($request);
	}
	
        /**
         * Abstract method for implementing some action in subclasses
         * @param Request $request
         */
	abstract function doExecute(Request $request);
}

?>
