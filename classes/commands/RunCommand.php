<?php

require_once "classes/Command.php";
require_once "classes/DetailsBuilder.php";
require_once 'classes/Run.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RunCommand
 *
 * @author xmiklos
 */
class RunCommand extends Command {
    
        function doExecute(Request $request)
	{
                if($request->getProperty("compile"))
                {
                    $run = new Run($request);
                    $run->compile();
                }
                else if($request->getProperty("run"))
                {
                    $run = new Run($request);
                    $run->run();
                }
                else if($request->getProperty("reset"))
                {
                    $run = new Run($request);
                    $run->reset();
                }
                else
                {
                    $details = new DetailsBuilder($request);
                    $details->run_params();
                }
	}
}

?>
