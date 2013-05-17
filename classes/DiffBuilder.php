<?php
/**
 * DiffBuilder class
 * @package
 */

require_once "Request.php";
require_once "DiffSubmission.php";
require_once "classes/PageBuilder.php";

require_once 'highlighter/Highlighter.php';
require_once 'highlighter/Highlighter/Renderer/Html.php';

/**
 * Class provides methods for displaying formated differences beetween submissions
 */
class DiffBuilder extends PageBuilder
{

/**
 * DiffSubmissions instance
 * @var DiffSubmission
 */
private $diff;

/**
 * Creates DiffSubmission instance
 * 
 * @param Request $request
 */
function __construct(Request $request)
{
	$this->diff = new DiffSubmission($request);
}

/**
 * Generates list of changed files
 * 
 */
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

/**
 * generates formated diff output for each changed file
 * 
 */
function diff_output()
{	
	$empty=true;
	
	foreach($this->diff->req_files as $key)
	{
		if($this->diff->diff_return[$key] > 0)
		{
			$empty=false;
			$content = $this->diff->diff_content[$key];
			echo "<div id='{$key}' class='diff_diff'>"; 
			$this->nice_diff($content);
			echo "</div>";
		}
	}
	
	if($empty) echo "<div id='error'>No files changed!</div>";
}

/**
 * displays information about compared submissions
 */
function diff_info()
{
	echo "Diff: {$this->parse_sub_str($this->diff->sub1)} <strong>vs.</strong> {$this->parse_sub_str($this->diff->sub2)}";
}

/**
 * Static method, formates unified diff output
 * 
 * @param string $diff input string
 * @param boolean $hl_on sets whether syntax highlighting is enabled 
 */
static function nice_diff($diff, $hl_on = true)
{
	$lines = explode("\n", $diff);
	$ln1 = 0; $ln2 = 0;
	$first = true;
	
	//$options = array('tabsize' => 4);
	$renderer = new Text_Highlighter_Renderer_HTML();
	$hl = Text_Highlighter::factory('cpp');
	$hl->setRenderer($renderer);
	
	echo "<pre>";
	foreach($lines as $line)
	{	
		$first3 = substr($line, 0, 3);
		if($first3 == "+++" || $first3 == "---")
		{
			continue;
		}
		$first2 = substr($line, 0, 2);
		if($first2 == "@@")
		{
			if(!$first) echo "</table>";
			$first = false;
			echo "<table>";
			echo "<tr>";
			echo "<td class='diff_head'>...</td><td class='diff_head'>...</td>";
			echo "<td class='diff_head'>$line</td>";
			echo "</tr>";
			
			$parts = explode(" ", $line);
			$f1 = substr($parts[1], 1);
			$f2 = substr($parts[2], 1);
			$num1 = explode(",", $f1);
			$num2 = explode(",", $f2);
			
			$ln1 = --$num1[0];
			$len1 = $num1[1];
			
			$ln2 = --$num2[0];
			$len2 = $num2[1];
			
			continue;
		}
		$first1 = substr($line, 0, 1);
		if($hl_on) $line = $hl->highlight($line);
		//$line = str_replace(" ", '&nbsp;', $line);
		if($first1 == "-")
		{
			$ln1++;
		
			echo "<tr >";
			echo "<td class='diff_ln' >$ln1</td><td class='diff_ln'>&nbsp;</td>";
			echo "<td class='diff_minus' >&nbsp;$line</td>";
			echo "</tr>";
		}
		else if ($first1 == "+")
		{
			$ln2++;
		
			echo "<tr >";
			echo "<td class='diff_ln'>&nbsp;</td><td class='diff_ln'>$ln2</td>";
			echo "<td class='diff_plus' >&nbsp;$line</td>";
			echo "</tr>";	
		}
		else
		{
			$ln1++; $ln2++;
		
			echo "<tr class='diff_line'>";
			echo "<td class='diff_ln'>$ln1</td><td class='diff_ln'>$ln2</td>";
			echo "<td>&nbsp;$line</td>";
			echo "</tr>";
		}
	}
	
	echo "</table></pre>";
}

/**
 * parses submission identifier string and returns human readable form
 * 
 * @param string $str
 * @return string
 */
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