<?php
/**
 * DetailsBuilder class
 * @package
 */

require_once "Request.php";
require_once "Details.php";
require_once "classes/PageBuilder.php";
require_once "classes/File.php";
require_once "classes/DiffBuilder.php";

require_once 'highlighter/Highlighter.php';
require_once 'highlighter/Highlighter/Renderer/Html.php';

/**
 * DetailsBuilder class provides metods for html output generation of detailed info.
 */
class DetailsBuilder extends PageBuilder
{

/**
 * Details object
 * 
 * @var Details
 */
private $details;

/**
 * Submission folder identifier
 * 
 * @var string 
 */
private $sub_folder;

/**
 * Subject
 * 
 * @var string 
 */
private $subject;

/**
 * Task
 * @var string 
 */
private $task;

/**
 * Constructor
 * 
 * @param Request $request
 */
function __construct(Request $request)
{
	$this->sub_folder = $request->getProperty('sub_folder');
	$this->subject = $request->getProperty('subject');
	$this->task = $request->getProperty('task');

	$this->details = new Details($request);
}

/**
 * echoes information about displayed submission details
 */
function details_info()
{
	echo "Subbmission: {$this->subject} - {$this->task} - {$this->parse_sub_str($this->sub_folder)}";
}

/**
 * generates list of tests as tabs
 * 
 */
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

/**
 * echoes two column table row
 * @param string $a
 * @param string $b
 */
private function table_row($a, $b)
{
	echo "<tr><td>{$a}</td><td><div class='wrap' >{$b}</div></td></tr>";
}

/**
 * returns html list of files
 * 
 * @param array $files
 * @param string $path
 * @return string
 */
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

/**
 * returns html element contaings link to download or display file
 * 
 * @param string $name
 * @param string $path
 * @param boolean $explicit_dp whether path is explicit in data attribute
 * @return string
 */
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

/**
 * generates gets student and test files and generates html list of them
 */
function sources()
{
	$student_files = array();
	$test_files = array();
	
	if(!is_array($this->details->get_tests()))
	{
		$stg = Config::get_setting("stage_dir");
		$dir = "{$stg}{$this->subject}/{$this->task}/{$this->sub_folder}/";
		$all_files = File::get_directory_files($dir);
		$req_files = File::get_required_files($this->subject, $this->task);
		
		if(count($all_files) > 0)
		{
			foreach($req_files as $key)
			{
				$student_files[] = $dir.$all_files[$key];
				unset($all_files[$key]);
			}
		
			$exts = Config::get_array_setting('source_files_ext');
		
			$keys = array_keys($all_files);
			foreach($keys as $key)
			{
				$value = $all_files[$key];
				$ext = pathinfo($key, PATHINFO_EXTENSION);
				//if(!in_array($ext, $exts)) continue;
				$test_files[] = $dir.$value;
			}
		}
	}
	else
	{
		$student_files = $this->details->get_student_files();
		$test_files = $this->details->get_test_files();
	}
	
	
	$this->sources_tabs($student_files, $test_files);
	
}

/**
 * generates list of tabs containg links for displaying source files
 * 
 * @param array $student_files
 * @param array $teacher_files
 */
private function sources_tabs(&$student_files, &$teacher_files)
{
	$keys = array_keys($student_files);
	
	if(count($student_files) == 0 && count($teacher_files) == 0)
	{
		echo "No files found!";
		return;
	}
	
	echo "<ul class='details_sources_list' >";
	foreach($keys as $key)
	{
		$value = $student_files[$key];
		$base = basename($value);
		$link = "?what=Details&path=&file={$value}";
		echo "<li class='test_li' >";
		echo "<div class='signals'><div class='square blue' title='Student file' ></div></div>";
		echo"<a href='{$link}#{$key}'>{$base}</a></li>";
	}
	
	
	$exts = Config::get_array_setting('source_files_ext');
	
	$keys = array_keys($teacher_files);
	
	foreach($keys as $key)
	{
		$value = $teacher_files[$key];
		$ext = pathinfo($value, PATHINFO_EXTENSION);
		if(!in_array($ext, $exts)) continue;
		$base = basename($value);
		$link = "?what=Details&path=&file={$value}";
		echo "<li class='test_li' >";
		echo "<div class='signals'><div class=' square red' title='Test file' ></div></div>";
		echo "<a href='{$link}#{$key}'>{$base}</a></li>";
	}
	echo "</ul>";
	
}

/**
 * generates misc tab content
 */
function misc()
{
	$files = array('teacher_email', 'student_email', 'kontr.pl');
	
	$stg = Config::get_setting("stage_dir");
	$path = "{$stg}{$this->subject}/{$this->task}/{$this->sub_folder}";

	echo "<ul class='details_misc_list' >";
	foreach($files as $file)
	{
		$link = "?what=Details&path={$path}&file={$file}";
		echo "<li class='test_li' ><a href='{$link}'>{$file}</a></li>";
	}
	echo "</ul>";
}
/*
function run()
{

}
*/

/**
 * Checks whether param haystack ends with needle
 * 
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
private function ends_with($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    
    return (substr($haystack, -$length) === $needle);
}

/**
 * Displays contents of file in dir folder
 * hightlights source files
 * 
 * @param string $dir
 * @param string $file
 */
function show_file($dir, $file)
{
	$c_ext = Config::get_array_setting('source_files_ext');
	$hl = null;
	$filepath = "{$dir}".($dir!=""?"/":"")."{$file}";
	$ext = pathinfo($filepath, PATHINFO_EXTENSION);
	
	//pl
	if($ext == "pl")
	{
		$hl = Text_Highlighter::factory('perl');
	}
	else if(in_array($ext, $c_ext))
	{
		$hl = Text_Highlighter::factory('cpp');
	}
	
	if(File::is_kontr_file($filepath)===false)
        {
            die("File is not in kontr working directory!");
        }
	
	$contents = File::load_file($filepath);
	
	if(basename($filepath) == "teacher_email")
	{
		
	}

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
	else
	{
		$contents = htmlentities($contents);
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
			if($line == "")
			{
				echo "<div>&nbsp;</div>";
			}
			else
			{
				echo "<div>{$line}</div>";
			}
			$i++;
		}
	echo "</pre></td></tr></table></div>";
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

function run_student_files()
{
    if(!is_array($this->details->get_tests()))
	{
		echo "Error: probably details.json file is missing!";
		return;
	}
    
    $student_files = $this->details->get_student_files();
    
    echo "<div class='run_files_scroll'>";
    foreach ($student_files as $key => $value) {
        //$base = base64_encode($key);
        echo "<span class='run_file'>";
        echo "<input class='run_file_selector' data-file='{$key}' type='checkbox' />";
        $link = "?what=Editor&load=load&key={$key}&task={$this->task}&subject={$this->subject}&sub_folder={$this->sub_folder}";
        echo "<span class='run_file_edit cp' ><a href='{$link}' target='_blank'>{$key}</a></span>";
        echo "</span><br />";
    }
    echo "</div>";
}

function run_test_files()
{
    if(!is_array($this->details->get_tests()))
	{
		echo "Error: probably details.json file is missing!";
		return;
	}
    
    $test_files = $this->details->get_test_files();
    $exts = Config::get_array_setting('source_files_ext');
    
    echo "<div class='run_files_scroll'>";
    foreach ($test_files as $key => $value) {
        //$ext = pathinfo($value, PATHINFO_EXTENSION);
	//if(!in_array($ext, $exts)) continue;
        //$base = base64_encode($key);
        echo "<span class='run_file'>";
        echo "<input class='run_file_selector' data-file='{$key}' type='checkbox' />";
        $link = "?what=Editor&load=load&key={$key}&task={$this->task}&subject={$this->subject}&sub_folder={$this->sub_folder}";
        echo "<span class='run_file_edit cp' ><a href='{$link}' target='_blank'>{$key}</a></span>";
        echo "</span><br />";
    }
    echo "</div>";
}

function run_tests()
{
    if(!is_array($this->details->get_tests()))
	{
		echo "Error: probably details.json file is missing!";
		return;
	}
    
    $test_files = $this->details->get_tests();
    
    echo "<div class='run_files_scroll'>";
    foreach($test_files as $test)
    {
        if($test->get_action("run") === false)
        {
            continue;
        }
        $test_name = $test->unit_test;
        if($test->sub_test !== false) $test_name.="/{$test->sub_test}";
        echo "<span class='run_file'>";
        echo "<input type='radio' name='test' class='run_test_list' data-test='{$test_name}' value='male'>";
        echo $test_name;
        echo "</span><br />";
    }
    echo "</div>";
}

function run_params()
{
    echo json_encode($this->details->get_run_params());
}



}

?>