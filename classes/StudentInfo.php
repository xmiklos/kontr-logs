<?php
require_once "classes/Config.php";
require_once "classes/File.php";

class StudentInfo
{

	private static $info;
	
	private function __construct() {}
	
	private static function try_load()
	{
		if(!isset(self::$info))
		{
			$contents = File::load_file(Config::get_setting("student_info_file"));
			$pieces = explode("\n", $contents);
		
			foreach($pieces as $value)
			{
				$parts = explode(",", $value);
				if(count($parts) != 6) continue;
				
				$student = array();
				$login = $parts[0];
				$student['tutor'] = $parts[5];
				$student['uco'] = $parts[2];
				$student['full_name'] = $parts[4];
				
				self::$info[$login] = $student;
			}
		}
	}
	
	static function get($key, $info)
	{
		self::try_load();
		
		if(array_key_exists($key, self::$info))
		{
			return self::$info[$key][$info];
		}
		else
		{
			return false;
		}
		
	}
	
	static function exists($key)
	{
		self::try_load();
		
		return array_key_exists($key, self::$info);
		
	}

}

?>
