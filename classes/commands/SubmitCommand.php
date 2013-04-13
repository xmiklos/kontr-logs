
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
		
		if(!$subject || !$task || !$subs)
		{
			echo "Incorrect request! - missing properties";
			exit;
		}
		
		if(!isset($login) || $login == "")
		{
			$login = `whoami`;
		}
		
		$subs = explode(" ", $subs);
		$filepath = Config::get_setting('submitted_internal');
		
		foreach($subs as $sub)
		{
			$exp = explode(":", $sub);
			$source_login = $exp[0];
			$revision = $exp[1];
			$type = $exp[2];
			
			$data = "[SVN]\nrevision={$revision}\nsource={$source_login}";
			$filename = "{$filepath}/{$subject}_{$type}_{$source_login}_{$task}";
			
			if(file_exists($filename))
			{
				echo "<div>Error: submission {$task} of {$source_login} as {$type} exists!</div>";
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
		
		
		
	}
}

?>
