<?php

require_once "LogParser.php";
require_once "File.php";
require_once "Request.php";
require_once "classes/PageBuilder.php";

class LogBuilder extends PageBuilder
{

public $parser;
private $task;
private $subject;
private $all;

function __construct(Request $request)
{
	parent::__construct($request);
	$logfile = Config::get_setting("report_path");
	$log = File::load_file($logfile);
	
	if($this->request_ok($request))
	{
		$this->parser = new LogParser($log, $request);
	}
	else
	{
		$this->parser = false;
	}
	
	$this->all = $request->getProperty('all');
}

private function request_ok(Request $request)
{
	$t = $request->getProperty('task');
	$s = $request->getProperty('subject');
	
	return ($t && $s);
}

function show()
{
	if(!$this->parser) return;

	foreach($this->parser->parse_as_stud() as $student)
	{
		$count = $student->count_subs();
		
		echo "<div class='{$student->get_classes()} ' id='u_{$student->name}'>";
		echo "<div class='open_std cp'><span class='number'></span>";
		$tutor = TeacherInfo::get($student->tutor, 'full_name');
		$desc = "{$student->full_name}";
		if($student->is_special)
		{
			$desc .= ", special user";
		}
		else
		{
			$desc .= ", Tutor: {$tutor}";
		}
		
		echo "<span class='std' title='{$desc}' >{$student->name}</span>";
		echo "<span class='vpravo'>";
			echo "<span class='green'>{$count[1]}</span> / <span class='yellow'>{$count[0]}</span>";
		echo "</span>";
		
		if($student->uco !== false)
		{
			echo "<span class='cp vpravo'><a href='https://is.muni.cz/auth/osoba/{$student->uco}' target='_blank'>[IS]</a>&nbsp;</span>";
		}
		
		if($student->has_new_subs)
		{
			echo "<span class='vpravo new_sub' >NEW!&nbsp;</span>";
		}
		
			echo "</div>";
			echo "<div class='odes' id='{$student->name}'>";
				foreach($student->submissions as $sub)
				{
					$name = ($sub->resubmitted_name?$sub->resubmitted_name:$student->name);
					echo "<p class='{$sub->get_classes()}' id='{$sub->folder}'".
					" data-login='{$name}'".
					" data-revision='{$sub->revision}' data-type='{$sub->test_type}' data-tags='{$sub->tags}' >";
						echo "<input class='submission_selector' type='checkbox' />";
						echo $sub->date;
						echo "&nbsp;";
						echo "<span class='summary {$sub->get_summary_classes()}' >";
						
							echo "<span style='display: none'>";
							echo "<span>SVN Revision: {$sub->revision}</span><br />";
							foreach($sub->unit_tests as $test)
							{
								echo "<span class='{$test->get_classes()}'>{$test->text}</span><br />";
							}
							echo "</span>";
						
						echo $sub->summary;
						echo "</span>";
						if($sub->resubmitted_name) echo "<span> Submission of {$sub->resubmitted_name}, rev. {$sub->revision}</span>";
						echo "<span class='open_details cp vpravo' > [details]</span>";
						if($sub->is_new)
						{
							echo "<span class='vpravo new_sub' >NEW!&nbsp;</span>";
						}
					echo "</p>";
				}
			echo "</div>";
		echo "</div>";
	}
	
	if($this->all !== false && $this->all == "all") $this->test_stats();
}

private function test_stats()
{
	$keys = array_keys(UnitTest::$success_count);
	
	echo "</div><div class='test_stats' style='margin-top: 15px;'><div class='open_test_stats cp'>";
	$title = 'This table is updated only on explicit Refresh, not when filter is applied.';
	echo "<span class='std' title='{$title}' >Tests success</span></div>";
	echo "<div class='odes' style='border: 0;'><table class='test_stats_table' >";
	foreach($keys as $key)
	{
		$val = UnitTest::$success_count[$key];
		
		echo "<tr><td ><strong style='padding-right: 25px;'>"; 
		echo $key;
		echo ":</strong></td><td style='text-align: right'>";
		echo $val;
		echo "</td></tr>";
		
	}
	echo "</table></div></div><div class>";
}

function show_notif()
{
	if(!$this->parser) return;
	$i=1;
	foreach($this->parser->parse_as_sub() as $sub)
	{
		echo "<div class='' id=''><span class='cp update_student' data-user='{$sub->name}' >{$i}. {$sub->date} {$sub->name}</span></div>";
		$i++;
	}
}

}

?>
