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

function __construct(Request $request)
{
	parent::__construct($request);
	$logfile = Config::instance()->get_setting("report_path");
	$log = File::load_file($logfile);
	
	if($this->request_ok($request))
	{
		$this->parser = new LogParser($log, $request);
	}
	else
	{
		$this->parser = false;
	}
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
		echo "<span class='number'></span>";
		echo "<span class='std'>{$student->name}</span>";
		echo "<span class='vpravo'>";
			echo "<span class='green'>{$count[1]}</span> / <span class='yellow'>{$count[0]}</span>";
		echo "</span>";
		echo "<span class='cp vpravo'><a href='https://is.muni.cz/auth/osoba/{$student->uco}' target='_blank'>[IS]</a>&nbsp;</span>";
		
			echo "<div class='odes' id='{$student->name}'>";
				foreach($student->submissions as $sub)
				{
					echo "<p class='{$sub->get_classes()}' id='{$sub->folder}' >";
						echo "<input class='submission_selector' type='checkbox' />";
						echo $sub->date;
						echo "&nbsp;";
						echo "<span class='summary {$sub->get_summary_classes()}' >";
						
							echo "<span style='display: none'>";
							foreach($sub->unit_tests as $test)
							{
								echo "<span class='{$test->get_classes()}'>{$test->text}</span><br />";
							}
							echo "</span>";
						
						echo $sub->summary;
						echo "</span>";
						echo "<span class='opensources cp vpravo' > [sources]</span>";
					echo "</p>";
				}
			echo "</div>";
		echo "</div>";
	}

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
