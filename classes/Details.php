<?php
/**
 * Details class
 * @package
 */

require_once "Config.php";
require_once "File.php";
require_once "Test.php";
require_once 'RunParams.php';

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
		
		if(!$sub_folder) return false;
		
		$filepath = "{$stage_dir}{$subject}/{$task}/{$sub_folder}/detailed.json";
		$file_content = File::load_file($filepath);
                
                if($file_content === false)
                {
                    return false;
                }
		
		$this->detailed_array = json_decode($file_content, true);
		
		if($this->detailed_array !== null)
		{
			$this->create_tests();
		}
                else
                {
                    $this->tests = false;
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
					
					if(array_key_exists('name', $sub_test) && $sub_test['name'] != "")
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
        
        function get_run_params()
        {
            $params_array = array();
            $last_flags=false;
            $flags_array;
            $flags;
            $args;
            $stdin;
            $name;
            
            foreach($this->tests as $test)
            {
                
                $run = $test->get_action('run');
                $cplt = $test->get_action('compilation');
                if($run === false)
                {
                    if($cplt !== false)
                    {
                        $last_flags = $cplt['args'];
                    }
                    
                    continue;
                }
                else
                {
                    if($cplt === false)
                    {
                        if($last_flags !== false)
                        {
                            $flags_array = $last_flags;
                        }
                    }
                    else
                    {
                        $flags_array = $cplt['args'];
                    }
                    $flags = array();
                    
                    foreach($flags_array as $flag)
                    {
                        if($flag[0] == '-' && $flag != "-o")
                        {
                            $flags[] =  $flag;
                        }
                    }
                    $bin = $test->work_path."/".$test->unit_test;
                    //echo $bin;
                    if(in_array($bin, $run['args']))
                    {
                        $ar = $run['args'];
                        $i=0;
                        for(; $i < count($ar); $i++)
                        {
                            if($ar[$i] == $bin)
                            {
                                break;
                            }
                        }
                        
                        $args = implode(" ", array_slice($ar, $i+1));
                    }
                    else
                    {
                        $args = implode(" ", $run['args']);
                    }
                    $stdin = $run['stdin'];
                    $name = $test->unit_test;
                    if($test->sub_test !== false) $name.="/{$test->sub_test}";
                    
                    $params = new RunParams();
                    $params->name = $name;
                    $params->run_args = $args;
                    $params->stdin = $stdin;
                    $params->cplt_flags = implode(" ", $flags);
                    
                    foreach($test->compiled_student_files as $file)
                    {
                        $params->files[] = $file;
                    }
                    
                    foreach($test->compiled_files as $file)
                    {
                        $params->files[] = $file;
                    }
                    
                    $params_array[] = $params;
                }
            }
            
            return $params_array;
        }

}


?>
