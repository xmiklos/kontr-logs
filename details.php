<div class="details_layer">
	<button id="details_close">Close</button>
	<span class='ui-state-highlight ui-corner-all details_info'>
		<?php $this->details_info();  ?>
	</span>
	<div id="details_tabs">
	<ul>
    		<li><a href="#details_tests">tests</a></li>
    		<li><a href="#details_sources">sources</a></li>
		<li><a href="#details_misc">emails & other</a></li>
		<li><a href="#details_run">run</a></li>
	</ul>
	<div id="details_tests" >
		<?php $this->tests(); ?>
	</div>
	<div id="details_sources" >
		<?php $this->sources(); ?>
	</div>
	<div id="details_misc" >
		<?php $this->misc(); ?>
	</div>
	<div id="details_run" >
	<table class='run_table' >
            <tr>
                <th>Tests</th>
                <th>Student files</th>
                <th>Test files</th>
                <th>Run</th>
                <tr/>
            <tr>
                <td > <?php $this->run_tests(); ?></td>
                <td > <?php $this->run_student_files(); ?></td>
                <td > <?php $this->run_test_files(); ?> </td>
                <td>
                    <fieldset>
                        <legend><button class="compile_button" >Compile</button></legend>
                        Compilation flags:<br>
                        <input type="text" class="cmpl_flags" size="60" >
                    </fieldset>
                    <fieldset>
                        <legend><button class="run_button">Run</button></legend>
                        with valgrind: <input type="checkbox" class="use_grind" ><br>
                        stdin file:<br>
                        <input type="text" class="stdin_file" size="60" ><br>
                        arguments:<br>
                        <input type="text" class="run_args" size="60" >
                    </fieldset>
                    
                </td>
                <tr/>
        </table>
            <fieldset><legend>Output:</legend><pre><div class="run_output"></div></pre></fieldset>
	</div>
	</div>
</div>
