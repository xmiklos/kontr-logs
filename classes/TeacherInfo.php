<?php
require_once "classes/Config.php";
require_once "classes/File.php";

class TeacherInfo
{

	private static $info;
	
	private function __construct() {}
	
	private static function try_load()
	{
		if(!isset(self::$info))
		{			
			$contents = File::load_file(Config::get_setting("teacher_info_file"));
			$pieces = explode("\n", $contents);
		
			foreach($pieces as $value)
			{
				$parts = explode(",", $value);
				if(count($parts) != 4) continue;
				
				$teacher = array();
				$login = $parts[0];
				$teacher['uco'] = $parts[1];
				$teacher['full_name'] = $parts[3];
				
				self::$info[$login] = $teacher;
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
