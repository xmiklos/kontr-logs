<?php
/**
 * Details class
 * @package
 */

require_once "Config.php";
require_once "File.php";
require_once "Test.php";

/**
 * Class parses input .json file and creates instances of Test class
 */
class Details
{
        /**
         * array of decoded json string
         * @var array
         */
	private $detailed_array;
	
        /**
         * list of tests
         * @var array of Test objects
         */
	private $tests; 
        
        /**
         * Constructor of class parses json file according to request parameters
         * and creates and stores Test instances
         * 
         * @param Request $request
         * @return null on failure
         */
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
	
        /**
         * Private method that creates tests from array decoded from json string
         */
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
	
        /**
         * returns student files used in all tests
         * 
         * @return array
         */
	function get_student_files()
	{
		$files = array();
	
		foreach($this->tests as $test)
		{
			foreach($test->staged_student_files as $file)
			{
				$filepath = "{$test->work_path}/{$file}";
				//$file_cont = File::load_file($filepath);
				//$sha = sha1($file_cont);
				$sha = basename($filepath);
				
				if(!array_key_exists($sha, $files))
				{
					$files[$sha] = $filepath;
				}
			}
			
			foreach($test->compiled_student_files as $file)
			{
				$filepath = "{$test->work_path}/{$file}";
				//$file_cont = File::load_file($filepath);
				//$sha = sha1($file_cont);
				$sha = basename($filepath);
				
				if(!array_key_exists($sha, $files))
				{
					$files[$sha] = $filepath;
				}
			}
		}
		
		return $files;
	}
	
        /**
         * returns assoc. array of test source files used in all tests,
         * keys are filenames, values full paths
         * @return array
         */
	function get_test_files()
	{
		$files = array();
	
		foreach($this->tests as $test)
		{
			foreach($test->staged_files as $file)
			{
				$filepath = "{$test->work_path}/{$file}";
				//$file_cont = File::load_file($filepath);
				//$sha = sha1($file_cont);
				$sha = basename($filepath);
				
				if(!array_key_exists($sha, $files))
				{
					$files[$sha] = $filepath;
				}
			}
			
			foreach($test->compiled_files as $file)
			{
				$filepath = "{$test->work_path}/{$file}";
				//$file_cont = File::load_file($filepath);
				//$sha = sha1($file_cont);
				$sha = basename($filepath);
				
				if(!array_key_exists($sha, $files))
				{
					$files[$sha] = $filepath;
				}
			}
		}
		
		return $files;
	}
	
        /**
         * returns list of Test objects
         * @return array
         */
	function get_tests()
	{
		return $this->tests;
	}

}


?>
