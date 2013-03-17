
<?php
require_once "classes/File.php";

abstract class PageBuilder
{
	private static $instance;
	private $request;

	final function __construct()
	{
		self::$instance = $this;
	}
	
	public static function instance()
	{
		return self::$instance;
	}
	
	function start($request)
	{
		ob_start();
		$this->request = $request;
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

