<?php
require_once "classes/IndexBuilder.php";

$i = $this;
$login = Auth::get_username();

?>
<div id="panel_enabler" ></div>
<div id="head">
	(<span id="login"><?php echo $login; ?></span>)
	<div class='opt' style="border: 0">
		<span>Choose task and subject:</span>

		<select name="task">
		<?php $i->option_tasks(); ?>
		</select>

		<select name="subject">
		<?php $i->option_subjects(); ?>
		</select>

		<input id="go_button" type="button" value="Send / Refresh">

		<a class="vpravo" href="#bottom">[bottom]</a><a class="vpravo" href="#top">[top]</a>
	</div>


	<div class='opt'>
		<form>
		Show students with:
		<select id="studfilter" >
		  <option  value="">all</option>
		  <option  value=".nanecisto">nanecisto only submissions</option>
		  <option  value=".naostroe">naostro submissions points&lt;6</option>
		  <option  value=".naostro">naostro submissions </option>
		  <option  value=".naostro6b">naostro submissions points=6</option>
		</select>
		Seminar tutor:
		<select id="tutorfilter" >
		<option  value="">all</option>
		<?php $i->option_tutors(); ?>
		</select>
		</form>
	</div>
	<div class='opt'>
		<form>
		Show submissions:
		<select id="subfilter" >
		  <option  value=".ode">all</option>
		  <option  value=".green">nanecisto only</option>
		  <option  value=".yellow">naostro only</option>
		</select>
		With tag:
		<select id="tagfilter" >
		  <option  value="">none</option>
		</select>
		</form>
	</div>
	<div class='opt'>
		Options:
		<span class="cp enable-notif" />[Enable notifications]</span>
		<span class="cp expand-all" />[Expand all]</span>
		<span class="cp do-diff" >[Diff]</span>
		<span class="cp sort-alpha" />[Sort alphabetically]</span>
		<span class="cp open-settings" />[Settings]</span>
		<span class="cp open-system-logs" />[Kontr system logs]</span>
		<span class="cp open-resub-dialog" />[Resubmission tool]</span>
	</div>
	<div></div>
	<div class='opt'>
		Legend:
		<span class='green'>nanecisto</span>
		<span class='yellow'>naostro</span>
		<span class='blue'>ok</span>
		<span class='red'>errors</span>
		<span class='purple'>bonus error only</span>
	</div>
</div>
<div id="space_filler" style=" height: 191px; display: none"></div>
<div id="students_wrapper">	
