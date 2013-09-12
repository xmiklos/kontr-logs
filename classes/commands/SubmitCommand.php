<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/Auth.php";
require_once "classes/Config.php";

class SubmitCommand extends Command
{
	function doExecute(Request $request)
	{
		$login = Auth::get_username();
		$subject = $request->getProperty('subject');
		$task = $request->getProperty('task');
		$subs = $request->getProperty('subs');
		$type = $request->getProperty('type');
		
		if(!$subject || !$task || !$subs || !$type)
		{
			echo "Incorrect request! - missing properties";
			exit;
		}
		
		$admins = Config::get_array_setting('admins');
		
		if($type == "submit" && !in_array($login, $admins))
		{
			echo "Only admin can do that!";
			exit;
		}
		
		/*if(!isset($login) || $login == "")
		{
			$login = `whoami`;
		}*/
		
		$subs = explode(" ", $subs);
		$filepath = Config::get_setting('submitted_internal');
		$emails = array();
		$teacher_emails = array();
		
		foreach($subs as $sub)
		{
			$exp = explode(":", $sub);
			$source_login = $exp[0];
			$revision = $exp[1];
			$subtype = $exp[2];

			if($type == "submit")
			{
				$login = $source_login;
				$email = StudentInfo::get($login, 'email');
				$tutor = StudentInfo::get($login, 'tutor');
				if($email === false)
				{
					$email = TeacherInfo::get($login, 'email');
				}
				if(!in_array($email, $emails))
				{
					$emails[] = $email;
				}
				
				if($tutor !== false)
				{
				    $teacher_email = TeacherInfo::get($tutor, 'email');
				    
				    if(!in_array($teacher_email, $teacher_emails))
				    {
				        $teacher_emails[$teacher_email] = $teacher_email;
				    }
				}
			}
			
			$data = "[SVN]\nrevision={$revision}\nsource={$source_login}";
			$filename = "{$filepath}/{$subject}_{$subtype}_{$login}_{$task}";
			
			if(file_exists($filename))
			{
				echo "<div>Error: submission {$task} of {$login} as {$subtype} already exists!</div>";
			}
			else
			{
				$ret = file_put_contents($filename, $data);
				
				if($ret === false)
				{
					echo "<div>Error submitting {$task} of {$source_login} with revision {$revision}.</div>";
				}
				else
				{
					echo "<div>OK! Submitted {$task} of {$source_login} with revision {$revision}.</div>";
				}
			}
			
		}
		
		if($type == "submit")
		{
			$std_emails = implode(", ", $emails);
			$tea_emails = implode(", ", $teacher_emails);
			echo "<div>Student emails: {$std_emails}</div>";
			echo "<div>Teacher emails: {$tea_emails}</div>";
		}		
	}
}

?>
