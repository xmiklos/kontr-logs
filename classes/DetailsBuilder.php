<?php

require_once "Request.php";
require_once "Details.php";
require_once "classes/PageBuilder.php";
require_once "classes/File.php";
require_once "classes/DiffBuilder.php";

require_once 'highlighter/Highlighter.php';
require_once 'highlighter/Highlighter/Renderer/Html.php';

class DetailsBuilder extends PageBuilder
{

private $details;
private $sub_folder;
private $subject;
private $task;

function __construct(Request $request)
{
	$this->sub_folder = $request->getProperty('sub_folder');
	$this->subject = $request->getProperty('subject');
	$this->task = $request->getProperty('task');

	$this->details = new Details($request);
}

function details_info()
{
	echo "Subbmission: {$this->subject} - {$this->task} - {$this->parse_sub_str($this->sub_folder)}";
}

function tests()
{	
	if(!is_array($this->details->get_tests()))
	{
		echo "There are no tests to display or file detailed.json does not exists!";
		return;
	}

	echo "<ul class='details_tests_list' >";
	
	foreach($this->details->get_tests() as $test)
	{
		$text = "{$test->unit_test}";
		$id = "{$test->master_test}_{$test->unit_test}";
		
		if($test->sub_test !== false)
		{
			if($test->sub_test != "") $text .= "/{$test->sub_test}";
			$id .= "_{$test->sub_test}";
		}
		
		echo "<li class='test_li' >";
		echo "<div class='signals'><div {$test->class_type()} ></div><div {$test->class_ok()} ></div></div>";
		echo"<a title='{$test->master_test} {$test->unit_test} {$test->sub_test}' href='#{$id}'>{$text}</a></li>";
	}
	
	echo "</ul>";
	
	$scripts = Config::get_setting("scripts_dir");
	$scripts_path = "{$scripts}{$this->subject}/{$this->task}";
	
	foreach($this->details->get_tests() as $test)
	{
		$id = "{$test->master_test}_{$test->unit_test}";
		
		if($test->sub_test !== false)
		{
			$id .= "_{$test->sub_test}";
		}
		
		echo "<div id='{$id}' class='test_content details_action' >";
			
			echo "<ul >";
			echo "<li ><a href='#{$id}_summary'>summary</a></li>";
			foreach($test->actions as $action)
			{
				echo "<li ><a href='#{$id}_{$action['name']}'>{$action['name']}</a></li>";
			}
			echo "</ul>";
			
			echo "<div id='{$id}_summary' class='details_sum' data-path='{$test->work_path}' data-sub='{$this->sub_folder}' ><table class='details_summary_table'>";
				$this->table_row("Master test", $test->master_test);
				$this->table_row("Unit test", $this->file_link($test->unit_test, $scripts_path, true));
				$this->table_row("Sub test", $test->sub_test);
				if(is_array($test->teacher_log_files) && count($test->teacher_log_files) > 0)
				{
					$offset = $test->teacher_log_files[0]['offset'];
					$inserted = 0;
					foreach($test->teacher_log_files as $entry)
					{
						$offset = $entry['offset'];
						$path_parts = pathinfo($entry['filename']);
						$base = $path_parts['basename'];
						$dir = $path_parts['dirname']."/";
						$link = "Logged file: ".$this->file_link($base, $dir, true);
						$test->log_teacher = substr_replace($test->log_teacher, $link, ($offset+$inserted), 0);
						$inserted += strlen($link);
					}
				}			
				$test->log_teacher = str_replace("\n", "<br />", trim($test->log_teacher));
				$this->table_row("Teacher log", $test->log_teacher);
				$this->table_row("Tags", implode(" ", $test->tags));
				$this->table_row("Points", implode(" ", $test->points));
				$this->table_row("Test files", $this->file_list($test->staged_files, $test->work_path));
				$this->table_row("Test compiled files", $this->file_list($test->compiled_files, $test->work_path));
				$this->table_row("Student files", $this->file_list($test->staged_student_files, $test->work_path));
				$this->table_row("Student compiled files", $this->file_list($test->compiled_student_files, $test->work_path));
			echo "</table><div class='details_ajax_data' ></div></div>";
			foreach($test->actions as $action)
			{
				echo "<div id='{$id}_{$action['name']}' class='details_sum' data-path='{$test->work_path}' data-sub='{$this->sub_folder}' ><table class='details_summary_table'>";
					$this->table_row("Action name", $action['name']);
					$this->table_row("Exit type", $action['exit_type']);
					$this->table_row("Exit value", $action['exit_value']);
					$this->table_row("Arguments", implode(" ", $action['args']));
					$this->table_row("Command", $action['command']." ".implode(" ", $action['args']));
					
					foreach(array_keys($action['metadata']) as $key)
					{
						$value = $action['metadata'][$key];
						if(substr($action['metadata'][$key], 0, 5) == "grind") $value = $this->file_link($action['metadata'][$key], $test->work_path);
						if($key == "first_file") $value = $this->file_link($action['metadata'][$key], "", true);
						$this->table_row($key, $value);
					}
					
					$this->table_row("stdin", $this->file_link($action['stdin'], $test->work_path));
					$this->table_row("stdout", $this->file_link($action['stdout'], $test->work_path));
					$this->table_row("stderr", $this->file_link($action['stderr'], $test->work_path));
					
					$this->table_row("Created files", $this->file_list($action['created_files'], $test->work_path));
					
				echo "</table><div class='details_ajax_data' ></div></div>";
			}
			
		echo "</div>";
	}
}

private function table_row($a, $b)
{
	echo "<tr><td>{$a}</td><td><div class='wrap' >{$b}</div></td></tr>";
}

private function file_list($files, $path)
{
	$buffer="<div><ul>";
	
	foreach($files as $file)
	{
		$buffer.="<li>{$this->file_link($file, $path)}</li>";
	}
	
	$buffer.="</ul></div>";
	
	return $buffer;
}

private function file_link($name, $path, $explicit_dp = false)
{
	$link = "?what=Details&path={$path}&file={$name}&download=true";
	$datapath="";
	if($explicit_dp)
	{
		$datapath = "data-path='{$path}'";
	}
	return "[<span class='details_show_file cp' {$datapath} >{$name}</span>][<a href='{$link}' >download</a>]";
}


function sources()
{
	
}

function sources_tabs()
{
	
}

function misc()
{

}

function run()
{

}

private function ends_with($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    
    return (substr($haystack, -$length) === $needle);
}

function show_file($dir, $file)
{
	$c_ext = array(".c", ".h", ".cpp", ".cc");
	$hl = null;
	$filepath = "{$dir}".($dir!=""?"/":"")."{$file}";
	
	// c,c++
	foreach($c_ext as $ext)
	{
		if($this->ends_with($file, $ext))
		{
			$hl = Text_Highlighter::factory('cpp');
			break;
		}
	}
	
	//pl
	if($this->ends_with($file, ".pl"))
	{
		$hl = Text_Highlighter::factory('perl');
	}
	

	/*
	$finfo = finfo_open(FILEINFO_MIME);
	//check to see if the mime-type starts with 'text'
	$s = substr(finfo_file($finfo, "{$dir}/{$file}"), 0, 4);
	if($s != 'text')
	{
		echo "<div class='ui-state-highlight ui-corner-all details_file_info'>File <strong>{$file}</strong> is binary.</div>";
		return;
	}
	*/
	
	File::is_kontr_file($filepath);
	
	$contents = File::load_file($filepath);

	if($contents == "")
	{
		echo "<div class='ui-state-highlight ui-corner-all details_file_info'>File <strong>{$file}</strong> is empty.</div>";
		return;
	}

	if(substr($file, 0, 10) == "difference")
	{
		DiffBuilder::nice_diff($contents, false);
		return;
	}

	if($hl != null)
	{
		$renderer = new Text_Highlighter_Renderer_HTML();
		$hl->setRenderer($renderer);
		$contents = $hl->highlight($contents);
	}
	$lines = explode("\n", $contents);

	echo "<div class='details_file_wrapper'><div class='details_file_heading' >{$file}</div><table ><tr><td class='details_ln' ><pre>";
		$c=count($lines);
		for($i = 1; $i <= $c; $i++)
		{
			if($i == $c && $lines[$i-1] == "") continue;
			echo "<div>{$i}</div>";
		}
	echo "</pre></td><td><pre>";
		$i=1;
		foreach($lines as $line)
		{
			if($line == "" && $i == $c) continue;
			if($line == "") $line="&nbsp;";
			//$line=htmlentities($line);
			echo "<div>{$line}</div>";
			$i++;
		}
	echo "</pre></td></tr></table></div>";
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
