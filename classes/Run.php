<?php

require_once 'Details.php';

/**
 * Description of Run
 *
 * @author xmiklos
 */
class Run {
    
    private $details;
    private $sub_folder;
    private $request;
    private $folder_path;
    private $files;
    private $files_base;
    private $tmp_folder;
    
    public function __construct($request) {
        $this->details = new Details($request);
        $this->request = $request;
        $this->sub_folder = $request->getProperty('sub_folder');
        $this->tmp_folder = "./tmp/";
        $this->clean_up();
        $this->create_tmp();
    }
    
    private function clean_up()
    {
        $tmp_dir = $this->tmp_folder;
        $files = File::get_directories($tmp_dir);
        $time = time();
        
        foreach($files as $file)
        {
            if(filemtime($tmp_dir.$file) + (24*3600) < $time)
            {
                system("rm -rf {$tmp_dir}{$file}");
            }
        }
    }
    
    private function create_tmp()
    {
        $tmp_folder = $this->tmp_folder;
        if(!file_exists($tmp_folder))
        {
            system("mkdir {$tmp_folder}");
        }
        
        $user = Auth::get_username();
        $run_folder = "{$user}_{$this->sub_folder}";
        $this->folder_path = $tmp_folder.$run_folder;
        
        $test_f = array_values($this->details->get_test_files());
        $student_f = array_values($this->details->get_student_files());
        $this->files = array_merge($test_f, $student_f);
        
        foreach($this->files as $file)
        {
            $this->files_base[] = basename($file);
        }
        
        if(!file_exists($this->folder_path))
        {
            system("mkdir {$this->folder_path}");
        }
        else
        {
            return false;
        }
        
        foreach($this->files as $file)
        {
            if(file_exists($file) === false)
            {
                $content = File::load_archived_stage_file($file);
                
                if($content !== false)
                {
                    $base = basename($file);
                    file_put_contents("{$this->folder_path}/{$base}", $content);
                }
                
            }
        }
        
        $files_string = implode(" ", $this->files);
        
        system("cp {$files_string} {$this->folder_path} &> /dev/null");
        
        return true;
    }
    
    static function safe_args(&$item, $key)
    {
        if(trim($item) != "")
        {
            $item = escapeshellarg($item);
        }
        else
        {
            $item = "";
        }
    }
    
    function compile()
    {
        $files = explode(" ", $this->request->getProperty("cmpl_files"));
        $flags = explode(" ", $this->request->getProperty("cmpl_flags"));
        
        if($files === false)
        {
            echo "No files to compile!";
            exit;
        }
        array_walk($files, 'Run::safe_args');
        array_walk($flags, 'Run::safe_args');
        $files = implode(" ", $files);
        $flags = implode(" ", $flags);
        
        $compiler = "gcc";
        
        $subject = $this->request->getProperty('subject');
        
        if($subject == "pb161")
        {
            $compiler = "g++";
        }
        
        $command = escapeshellcmd("{$compiler} {$flags} {$files}");
        
        $this->exec("cd {$this->folder_path}; ".$command);
    }
    
    function run()
    {
        $args = explode(" ", $this->request->getProperty("run_args"));
        $use_grind = $this->request->getProperty("valgrind");
        $stdin = escapeshellarg($this->request->getProperty("stdin_file"));
        
        if(!file_exists($this->folder_path."/a.out"))
        {
            echo "You have to compile first!";
            exit;
        }
        
        array_walk($args, 'Run::safe_args');
        $args = implode(" ", $args);
        $valgrind="";
        if($use_grind !== false && $use_grind == "valgrind")
        {
            $valgrind = "valgrind --leak-check=full --log-file=valgrind.out ";
        }
        
        $command = escapeshellcmd("{$valgrind} ./a.out {$args}");
        
        $this->exec("cd {$this->folder_path} \n ".$command, $stdin);
    }
    
    function exec($command, $stdin = "/dev/null")
    {
        shell_exec("{$command} > stdout.out 2> stderr.out < {$stdin} & \n echo $! > process.id");
        
        $ret=0;
	$counter=0;
	while(!$ret)
	{
		system("cd {$this->folder_path}; ps -p `cat process.id` &> /dev/null", $ret);
		sleep(1);
		$counter++;
		if($counter > 20)
		{
			system("cd {$this->folder_path}; kill -9 `cat process.id`");
			echo "<strong>!!! Beh programu bol nasilne ukonceny. Doba behu prekrocila povoleny limit (20s).</strong><br /><br />";
			break;
		}
	}
        
        if(file_exists("{$this->folder_path}/changed"))
        {
            echo "<strong class='reset_message'>!!! Changes has been made to your temporary files. To revert them click: <span class='reset_files cp'>reset</span></strong><br /><br />";
			
        }
        
        echo "<fieldset><legend>stdout</legend><pre>";
        $stdout = file_get_contents($this->folder_path."/"."stdout.out");
        if($stdout == "")
        {
            echo "(empty)";
        }
        else
        {
            echo htmlentities($stdout);
        }
        echo "</pre></fieldset>";
        
        echo "<fieldset><legend>stderr</legend><pre>";
        $stderr = file_get_contents($this->folder_path."/"."stderr.out");
        if($stderr == "")
        {
            echo "(empty)";
        }
        else
        {
            echo htmlentities($stderr);
        }
        echo "</pre></fieldset>";
        
        $use_grind = $this->request->getProperty("valgrind");
        
        if($use_grind !== false && $use_grind == "valgrind")
        {
            echo "<fieldset><legend>valgrind</legend><pre>";
            $valgrind = file_get_contents($this->folder_path."/"."valgrind.out");
            if($valgrind == "")
            {
                echo "(empty)";
            }
            else
            {
                echo htmlentities($valgrind);
            }
            echo "</pre></fieldset>";
        }
    }
    
    function load_file()
    {
        $file = $this->request->getProperty("key");
        $filepath = "{$this->folder_path}/{$file}";
        if(file_exists($filepath) === false || in_array($file, $this->files_base) === false || $file === false)
        {
            return false;
        }
        
        return htmlentities(file_get_contents($filepath));
    }
    
    function save_file()
    {
        $file = $this->request->getProperty("key");
        $data = $this->request->getProperty("data");
        $filepath = "{$this->folder_path}/{$file}";
        if(file_exists($filepath) === false || in_array($file, $this->files_base) === false || data === false || $file === false)
        {
            return false;
        }
        $ret = file_put_contents($filepath, $data);
        if($ret !== false)
        {
            file_put_contents("{$this->folder_path}/changed", "");
        }
        return $ret;
    }
    
    function reset()
    {
        system("rm -rf {$this->folder_path}", $val);
        
        if($val == 0 && $this->create_tmp() !== false)
        {
            echo "Folder resetted";
        }
    }
}
    
?>
