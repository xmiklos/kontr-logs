<?php
/**
 * StudentInfo class
 * @package
 */

require_once "classes/Config.php";
require_once "classes/File.php";

/**
 * Class provides means to get information about students
 */
class StudentInfo
{
        /**
         * Student info array
         * @var array 
         */
	private static $info;
	
        /**
         * Constructor
         */
	private function __construct() {}
	
        /**
         * Static method for information parsing from Kontr student list
         */
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
				$student['email'] = $parts[3];
				
				self::$info[$login] = $student;
			}
		}
	}
	
        /**
         * Method returns information about Student
         * 
         * @param string $key student login
         * @param type $info one of: tutor, uco, full_name, email
         * @return string on success, false on failure
         */
	static function get($key, $info)
	{
		self::try_load();
		
		if(array_key_exists($key, self::$info) && array_key_exists($info, self::$info[$key]))
		{
			return self::$info[$key][$info];
		}
		else
		{
			return false;
		}
		
	}
	
        /**
         * Method for determining that student exists in Kontr list
         * 
         * @param string $key student login
         * @return boolean
         */
	static function exists($key)
	{
		self::try_load();
		
		return array_key_exists($key, self::$info);
		
	}

}

?>
