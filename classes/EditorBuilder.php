<?php
require_once 'classes/PageBuilder.php';
require_once 'classes/Run.php';

/**
 * 
 *
 * @author xmiklos
 */
class EditorBuilder extends PageBuilder {
    function source()
    {
        $run = new Run($this->get_request());
        echo $run->load_file();
    }
    
    function data()
    {
        $task = $this->get_request()->getProperty('task');
        $subject = $this->get_request()->getProperty('subject');
        $folder = $this->get_request()->getProperty('sub_folder');
        $key = $this->get_request()->getProperty('key');
                
        echo " data-task='{$task}' data-subject='{$subject}' data-folder='{$folder}' data-key='{$key}' ";
    }
    
    function title()
    {
        $key = $this->get_request()->getProperty('key');
        echo $key;
    }
}

?>