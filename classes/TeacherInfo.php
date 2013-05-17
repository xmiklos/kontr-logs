<?php
/**
 * TeacherInfo class
 * @package
 */

require_once "classes/Config.php";
require_once "classes/File.php";

/**
 * Class provides means to get information about teachers
 */
class TeacherInfo
{
        /**
         * Holds list of teachers with their information
         * @var array
         */
	private static $info;
	
        /**
         * Constructor
         */
	private function __construct() {}
	
        /**
         * Static method for information parsing from Kontr teacher list
         */
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
				$teacher['email'] = $parts[2];
				$teacher['full_name'] = $parts[3];
				$teacher['tutor'] = "No Tutor";
				
				self::$info[$login] = $teacher;
			}
		}
	}
	
        /**
         * Method returns information about Teacher
         * 
         * @param string $key teacher login
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
         * Method for determining if teacher exists in Kontr list
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
