<?php
require_once "classes/File.php";

class PageBuilder
{
	private $request;

	function __construct($request)
	{
		$this->request = $request;
	}
	
	function start()
	{
		ob_start();
	}
	
	function incl($filename)
	{
		include_once $filename;
	}
	
	function text($text)
	{
		echo $text;
	}

	final function render()
	{
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}
	
	function get_request()
	{
		return $this->request;
	}
}

?>
