
<?php
require_once "classes/Config.php";

class File
{
	public static function load_file($file_path)
	{
		return file_get_contents($file_path);
	}
	
	public static function get_tasks($subject)
	{
		$kontr = Config::instance()->get_setting("kontr_path");
		$path=$kontr."_tmp_/".$subject."/";
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
	
	public static function get_subjects()
	{
		$values = Config::instance()->get_setting('subjects');
		
		return explode(" ", $values);
	}
	
	public static function get_tutors()
	{
		$contents = self::load_file(Config::get_setting("student_info_file"));
		$pieces = explode("\n", $contents);
		$ret = array();
		$student_tutor = array();
		$student_uco = array();
		
		foreach($pieces as $value)
		{
			$parts = explode(",", $value);
			if(count($parts) != 6) continue;

			$tutor = $parts[5];
			$student = $parts[0];
	
			$student_tutor[$student] = $tutor;
			$student_uco[$student] = $parts[2];
		}
		
		$ret['tutor'] = $student_tutor;
		$ret['uco'] = $student_uco;
		
		return $ret;
	}
}

?>
