<?php
require_once "classes/Request.php";
require_once "classes/Command.php";
require_once 'classes/EditorBuilder.php';

/**
 * 
 *
 * @author xmiklos
 */
class EditorCommand extends Command {
    function doExecute(Request $request)
	{
		if($request->getProperty("load"))
                {
                    $ed = new EditorBuilder($request);
                    $ed->start();
                    $ed->incl("editor.php");
                    $ed->render();
                }
                else if($request->getProperty("save"))
                {
                    $run = new Run($request);
                    $ret = $run->save_file();
                    if($ret === false)
                    {
                        echo "Error saving file!";
                    }
                    else
                    {
                        echo "File saved!";
                    }
                }
	}
}

?>