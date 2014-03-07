<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once "classes/DetailsBuilder.php";
require_once "classes/File.php";

class DetailsCommand extends Command
{
	function doExecute(Request $request)
	{
		$details = new DetailsBuilder($request);
		$details->start();
		
		$dir = $request->getProperty('path');
		$file = $request->getProperty('file');
		$download = $request->getProperty('download');
		$down_student_files = $request->getProperty('download_student_files');
		
		if($down_student_files !== false)
		{
			File::download_student_files($request, $details, $request->getProperty('as'));
		}
		else if($dir !== false && $file !== false && $download !== false)
		{
			File::download_file($dir, $file);
		}
		else if($dir !== false && $file !== false)
		{
			$details->show_file($dir, $file);
		}
		else
		{
			$details->incl("details.php");
		}
		$details->render();
	}
	
}

?>
