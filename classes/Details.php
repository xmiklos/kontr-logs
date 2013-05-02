<?php

require_once "Config.php";
require_once "File.php";
require_once "Test.php";

class Details
{
	private $detailed_array;
	
	private $tests; 

	function __construct($request)
	{
		$stage_dir = Config::get_setting('stage_dir');
		$subject = $request->getProperty('subject');
		$task = $request->getProperty('task');
		$sub_folder = $request->getProperty('sub_folder');
		
		if(!$sub_folder) return null;
		
		$filepath = "{$stage_dir}{$subject}/{$task}/{$sub_folder}/detailed.json";
		$file_content = File::load_file($filepath);
		
		$this->detailed_array = json_decode($file_content, true);
		
		if($this->detailed_array != null)
		{
			$this->create_tests();
		}
		
	}
	
	private function create_tests()
	{
		$properties = array('staged_files', 'compiled_files', 'staged_student_files', 'compiled_student_files');
	
		foreach($this->detailed_array as $master_test)
		{
			foreach($master_test['units'] as $unit_test)
			{
				foreach($unit_test['subtests'] as $sub_test)
				{
					$test = new Test();
					$test->master_test = $master_test['name'];
					$test->unit_test = $unit_test['name'];
					
					if(array_key_exists('name', $sub_test))
					{
						$test->sub_test = $sub_test['name'];
					}
					else
					{
						$test->sub_test = false;
					}
					
					foreach($properties as $prop)
					{
						if(array_key_exists($prop, $master_test))
						{
							$test->add_files($prop, $master_test[$prop]);
						}
						if(array_key_exists($prop, $unit_test))
						{
							$test->add_files($prop, $unit_test[$prop]);
						}
					}
					
					//$test->add_log('log_student', $master_test['student_log']);
					//$test->add_log('log_teacher', $master_test['teacher_log']);
					
					$test->add_log('log_student', $sub_test['student_log']);
					$test->add_log('log_teacher', $sub_test['teacher_log']);
					
					$test->teacher_log_files = $sub_test['teacher_log_files'];
					$test->student_log_files = $sub_test['student_log_files'];
					
					$test->tags = $sub_test['tags'];
					$test->points = $sub_test['points'];
					
					$test->actions = $sub_test['actions'];
					$test->work_path = $unit_test['work_path'];
					
					$this->tests[] = $test;
				}
			}	
		}
	}
	
	function get_tests()
	{
		return $this->tests;
	}

}


?>
