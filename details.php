<div class="details_layer">
	<button id="details_close">Close</button>
	<span class='ui-state-highlight ui-corner-all details_info'>
		<?php $this->details_info();  ?>
	</span>
	<div id="details_tabs">
	<ul>
    		<li><a href="#details_tests">tests</a></li>
    		<li><a href="#details_sources">sources</a></li>
		<li><a href="#details_misc">misc</a></li>
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
	
	</div>
	  
	</div>
</div>
