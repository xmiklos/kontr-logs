<?php
/**
 * File class
 * @package
 */

require_once "classes/Config.php";
require_once "tar/Tar.php";

/**
 * File class provides various methods for directory listing and file manipulation
 */
class File
{
        /**
         * Archive_Tar object instance
         * @var Archive_Tar
         */
	static $tar_cache;
        
        /**
         * Filename of tar archive object in tar_cache
         * @var string
         */
	static $tar_cache_filename;
        
        /**
         * method gets file contents
         * 
         * @param string $file_path
         * @return string content of loaded file, boolean false on failure
         */
	public static function load_file($file_path)
	{
		if(!file_exists($file_path))
		{
			return File::load_archived_stage_file($file_path);
		}
	
		return file_get_contents($file_path);
	}
	
        /**
         * method gets file contents from archived file in tar format
         * 
         * @param string $file_path
         * @return string content of loaded file, boolean false on failure
         */
	public static function load_archived_stage_file($file_path)
	{
                /*if(file_exists($file_path) === false)
                {
                    return false;
                }*/
                
		$stage = Config::get_setting("stage_dir");
                
		$pos1 = strpos($file_path, $stage);
                
                if($pos1 === false || $pos1 != 0)
                {
                    return false;
                }
                
		$file_stage_path = substr($file_path, strlen($stage));
		$tree = explode("/", $file_stage_path);
                
                if(count($tree) < 3)
                {
                    return false;
                }
                
		$subject = $tree[0];
		$task = $tree[1];
		$folder = $tree[2];
		
		$archive = "{$stage}{$subject}/{$task}/{$folder}.tar.bz2";
                /*if(File::is_kontr_file($archive) === false)
		{
			return false;
		}*/
		
		if(!isset(File::$tar_cache) || File::$tar_cache_filename != $archive)
		{
			File::$tar_cache = new Archive_Tar($archive);
			File::$tar_cache_filename = $archive;
		}		
		
		$tar_path = substr($file_path, 1);
                $ret = File::$tar_cache->extractInString("{$tar_path}");
                
		if($ret === null)
                {
                    return false;
                }
                else
                {
                    return $ret;
                }
		
	}
	
        /**
         * Method checks that file is in kontr directory, sets download headers
         * and sends file content to output
         * 
         * @param string $path
         * @param string $filename
         */
	public static function download_file($path, $filename)
	{
	
		if($path !== false && $path != "")
		{
			$path .= "/";
		}
		$file = "{$path}{$filename}";
		
		// security check
		if(File::is_kontr_file($file))
                {	
                    $filesize = filesize($file); 
                    $basename = basename($filename);
                    header("Content-Type: text/plain");
                    header("Content-Disposition: attachment; filename={$basename}");
                    header("Content-Length: $filesize");
                    //readfile($file);
                    echo File::load_file($file);
                }
                else
                {
                    die("File is not in kontr working directory or does not exist!");
                }
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

	public static function download_student_files(Request $request, $builder, $type = "tar")
	{
		$sub_folder = $request->getProperty('sub_folder');
		$a_filename = "tmp/{$sub_folder}";
		$details = $builder->get_details();
		$files = $details->get_student_files();
		
		if($type == "zip") {
			$a_filename .= ".zip";
			
			array_walk($files, 'File::safe_args');
			
			$files = implode(' ', $files);
			
			$esc_filename = escapeshellarg($a_filename);
			
			$command = "zip -j {$esc_filename} {$files}";
			
			`$command`;
		}
		else {
			$a_filename .= ".tar";
			$archive = new Archive_Tar($a_filename);
			
			
		
			foreach($files as $file)
			{
				$dir = dirname($file);
				$archive->addModify(array($file), '', $dir);
			}
		}
			
		$filesize = filesize($a_filename); 
                $basename = basename($a_filename);
                header("Content-Type: text/plain");
                header("Content-Disposition: attachment; filename={$basename}");
                header("Content-Length: $filesize");
                
                echo File::load_file($a_filename);
                
                unlink($a_filename);
	}
	
        /**
         * Method checks whether file is in kontr working directory
         * 
         * 
         * @param string $file
         * @return boolean 
         */
	public static function is_kontr_file($file)
	{
		$kontr = realpath(Config::get_setting("kontr_path"));
                
                $real_file = $file; //realpath($file);
                
                if($real_file === false)
                {
                    //try load archived file
                    $file_content = File::load_archived_stage_file($file);
                    //echo $file;
                    if($file_content === false)
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
		
		$pos1 = strpos($real_file, $kontr);
		
		if($pos1 === false || $pos1 != 0)
		{
			return false;
		}
                
                return true;
	}
	
        /**
         * Method gets all task code names for given subject
         * 
         * @param string $subject
         * @return array
         */
	public static function get_tasks($subject)
	{
		$kontr = Config::get_setting("kontr_path");
		$path=$kontr."_tmp_/".$subject."/";
		
		if(!file_exists($path))
		{
			return array();
		}
		
		$hand=opendir($path);
		$tasks=array();

		while (($fil = readdir($hand)))
		{
			if($fil == '.' || $fil == '..'){
				continue;
			}
			if(is_dir($path.$fil))
			{
				$tasks[]=$fil;
			}
		}

		closedir($hand);

		return $tasks;
	}
	
        /**
         * Method gets all subjects defined in config.ini
         * 
         * @return array
         */
	public static function get_subjects()
	{
		$values = Config::get_setting('subjects');
		
		return explode(" ", $values);
	}
	
        /**
         * Method returns assoc. array with three keys: tutor, uco, full_name
         * values of those keys are assoc. arrays with student login
         * 
         * @return array
         */
	public static function get_student_info()
	{
		$contents = self::load_file(Config::get_setting("student_info_file"));
		$pieces = explode("\n", $contents);
		$ret = array();
		$student_tutor = array();
		$student_uco = array();
		$student_full_name = array();
		
		foreach($pieces as $value)
		{
			$parts = explode(",", $value);
			if(count($parts) != 6) continue;

			$tutor = $parts[5];
			$student = $parts[0];
	
			$student_tutor[$student] = $tutor;
			$student_uco[$student] = $parts[2];
			$student_full_name[$student] = $parts[4];
		}
		
		$ret['tutor'] = $student_tutor;
		$ret['uco'] = $student_uco;
		$ret['full_name'] = $student_full_name;
		
		return $ret;
	}
	
        /**
         * Method retreives list of required files for given task and subject
         * 
         * @param string $subject
         * @param string $task
         * @return array
         */
	public static function get_required_files($subject, $task)
	{
		$files = Config::get_setting("files_dir");
		$req_file = "{$files}{$subject}/{$task}/required_files";
		$contents = self::load_file($req_file);
		
		$files = explode("\n", $contents);
		$ret = array();
		
		foreach($files as $file)
		{
			$f_name = trim($file);
			
			if($f_name!="")
			{
                                if(substr($f_name, 0, 1) == "?")
                                {
                                    $ret[] = substr($f_name, 1);
                                }
                                else
                                {
                                    $ret[] = $f_name;
                                }
				
			}
		}
		
		return $ret;
	}
	
        /**
         * Method recursivelly scans hierarchy of directory dir
         * and returns assoc. array with filenames as keys and filepaths as values
         * 
         * @param string $dir
         * @return array
         */
	public static function get_directory_files($dir)
	{
		if(!file_exists($dir))
		{
			return array();
		}
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
		$files = array();

		while($it->valid()) {

		    if (!$it->isDot() && !array_key_exists($it->getFilename(), $files)) {
			$files[$it->getFilename()]=$it->getSubPathName();
		    }

		    $it->next();
		}
		
		return $files;
	}
        
        /**
         * Gets directories in $dir only, not whole hierarchy
         * @param type $dir
         */
        public static function get_directories($dir)
        {
                if(file_exists($dir) === false)
                {
                    return array();
                }
            
                $hand=opendir($dir);
		$files=array();

		while (($fil = readdir($hand)))
		{
			if($fil == '.' || $fil == '..'){
				continue;
			}
			if(is_dir($dir.$fil))
			{
				$files[]=$fil;
			}
		}

		closedir($hand);
                
                return $files;
        }
}

?>
