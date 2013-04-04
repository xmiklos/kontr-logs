<?php

require_once "Request.php";
require_once "DiffSubmission.php";
require_once "classes/PageBuilder.php";

class DiffBuilder extends PageBuilder
{

private $diff;

function __construct(Request $request)
{
	$this->diff = new DiffSubmission($request);
}

function file_list()
{
	$empty=true;

	foreach($this->diff->req_files as $key)
	{
		if($this->diff->diff_return[$key] > 0)
		{
			$empty=false;
			echo "<li ><a href='#{$key}'>{$key}</a></li>";
		}
	}
	
	if($empty) echo "<li ><a href='#error'>diff</a></li>";
}

function diff_output()
{	
	$empty=true;
	
	foreach($this->diff->req_files as $key)
	{
		if($this->diff->diff_return[$key] > 0)
		{
			$empty=false;
			$content = $this->diff->diff_content[$key];
			$content = str_replace("\n", '<br />', $content);
			echo "<div id='{$key}'>{$content}</div>";
		}
	}
	
	if($empty) echo "<div id='error'>No files changed!</div>";
}

function diff_info()
{
	echo "Diff: {$this->parse_sub_str($this->diff->sub1)} <strong>vs.</strong> {$this->parse_sub_str($this->diff->sub2)}";
}

private function parse_sub_str($str)
{
	$date = explode("_", $str, 2);
	$p = explode("_", $date[1]);
	$rok = $p[0];
	$mesiac = substr($p[1], 0, 2);
	$den = substr($p[1], -2);

	$hod = substr($p[2], 0, 2);
	$min = substr($p[2], 2, 2);
	$sec = substr($p[2], -2);
	return "{$date[0]} - {$den}.{$mesiac} - {$hod}:{$min}:{$sec}";
}

}

?>
