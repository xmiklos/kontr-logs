<?php
require_once "classes/IndexBuilder.php";

$i = $this;
$login = Auth::get_username();

?>
<div id="panel_enabler" ></div>
<div id="head">
	(<?php echo $login; ?>)
	<div class='opt' style="border: 0">
		<form id="vyber" action="index.php" method="GET">
		<span>Choose task and subject:</span>

		<select name="task">
		<?php $i->option_tasks(); ?>
		</select>

		<select name="subject">
		<?php $i->option_subjects(); ?>
		</select>

		<input type="submit" value="GO">

		<a class="vpravo" href="#bottom">[bottom]</a><a class="vpravo" href="#top">[top]</a>
		</form>
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
		  <option  value="">all</option>
		  <option  value=".green">nanecisto only</option>
		  <option  value=".yellow">naostro only</option>
		</select>
		</form>
	</div>
	<div class='opt'>
		Options:
		<span class="cp enable-notif" />[Enable notifications]</span>
		<span class="cp expand-all" />[Expand all]</span>
		<span class="cp do-diff" >[diff selected submissions]</span>
		<span class="cp sort-alpha" />[Sort alphabetically]</span>
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
