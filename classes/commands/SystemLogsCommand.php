
<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/SystemLogsBuilder.php";
require_once "classes/File.php";
require_once "classes/KontrPlParser.php";
require_once "classes/StarterParser.php";


class SystemLogsCommand extends Command
{
	function doExecute(Request $request)
	{
		$log_type = $request->getProperty('log');
		
		if($log_type)
		{
			if($log_type == "kontr_pl")
			{
				$logfile = Config::get_setting("kontrpl_log");
				$log = File::load_file($logfile);
				$parser = new KontrPlParser($log);
			}
			else if($log_type == "starter_pl")
			{
				$logfile = Config::get_setting("starterpl_log");
				$log = File::load_file($logfile);
				$parser = new StarterParser($log);
			}
			else if($log_type == "starter_sh")
			{
				$logfile = Config::get_setting("startersh_log");
				$log = File::load_file($logfile);
				//$parser = new StarterParser($log);
				SystemLogsBuilder::simple_show($log);
				return;
			}
			else
			{
				echo "Incorrect request! - property log";
				return;
			}
			
			$log = new SystemLogsBuilder($request, $parser);
			$log->start();
			$log->show();
			$log->render();
		}
		else
		{
			require_once "system_logs.php";
		}
	}
}

?>
