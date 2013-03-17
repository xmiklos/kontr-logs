<?php

require_once "LogParser.php";
require_once "File.php";
require_once "Request.php";

class LogDisplay
{

public $parser;
private $task;
private $subject;

function __construct(Request $request)
{
	$logfile = Config::instance()->get_setting("report_path");
	$log = File::load_file($logfile);
	
	if($this->request_ok($request))
	{
		$this->parser = new LogParser($log, $request);
		$this->parser->parse();
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
	ob_start();

	foreach($this->parser->get_students() as $student)
	{
		$count = $student->count_subs();
		
		
		echo "<div class='{$student->get_classes()}' id='u_{$student->name}'>";
		echo "<span class='number'></span>";
		echo "<span class='std'>{$student->name}</span>";
		echo "<span class='vpravo'>";
			echo "<span class='green'>{$count[1]}</span> / <span class='yellow'>{$count[0]}</span>";
		echo "</span>";
		echo "<span class='cp vpravo'><a href='https://is.muni.cz/auth/osoba/{$student->uco}' target='_blank'>[IS]</a>&nbsp;</span>";
		
			echo "<div class='odes' id='{$student->name}'>";
				foreach($student->submissions as $sub)
				{
					echo "<p class='{$sub->get_classes()}' >";
						echo $sub->date;
						echo "<input id='{$sub->folder}' type='checkbox' />";
						echo "<span class='{$sub->get_summary_classes()}' >";
						
							echo "<span style='display: none'>";
							foreach($sub->unit_tests as $test)
							{
								echo "<span class='{$test->get_classes()}'>{$test->text}</span><br />";
							}
							echo "</span>";
						
						echo $sub->summary;
						echo "</span>";
						echo "<span class='cp vpravo' > [sources]</span>";
					echo "</p>";
				}
			echo "</div>";
		echo "</div>";
	}

	
	$contents = ob_get_contents();
	ob_end_clean();
	echo $contents;
}

}

?>
