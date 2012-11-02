#!/packages/run/php/bin/php
<?php

session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

if ((!isset($_SESSION['uloha'])) || (!isset($_SESSION['predmet'])))
{
echo 'uloha and predmet not set, exit';
exit;
}

function get_time($date)
{
	$p = explode("_", $date);
	$rok = $p[0];
	$mesiac = substr($p[1], 0, 2);
	$den = substr($p[1], -2);

	$hod = substr($p[2], 0, 2);
	$min = substr($p[2], 2, 2);
	$sec = substr($p[2], -2);

	return mktime($hod, $min, $sec, $mesiac, $den, $rok);
}

function nice_date($date)
{
	$p = explode("_", $date);
	$rok = $p[0];
	$mesiac = substr($p[1], 0, 2);
	$den = substr($p[1], -2);

	$hod = substr($p[2], 0, 2);
	$min = substr($p[2], 2, 2);
	$sec = substr($p[2], -2);

	return $den.".".$mesiac." - ".$hod.":".$min.":".$sec;
}

function get_user($line)
{
	$parts = explode(" ", $line, 3);

	return $parts[1];
}

function nice_stat($p, $poc)
{
	echo "<strong>".$p."</strong> - ".$poc."<br/>";
}

function nice_tests($line)
{	
	$str = "<div class=\\\"res\\\" >";
	$chunks = explode("#", $line);
	$prvy = false;
	foreach($chunks as $chunk)
{
	if($prvy)
	{
		
		$str = $str."<span class=\\\"".((strstr($chunk, 'ok') != false)?"blue":"red")."\\\">".$chunk."</span><br />";
	}
	$prvy = true;
}

	$str = $str."</div>";

return $str;
}

$uloha = $_SESSION['uloha'];
$predmet = $_SESSION['predmet'];
$sub = $predmet.' '.$uloha;
$ruser = $_REQUEST['user'];

ob_start();
system("grep -G '".$ruser.".*".$sub."' /home/xtoth1/kontrNG/_logs_/report.log");
$contents = ob_get_contents();
ob_end_clean();

$wholelines = explode("\n", $contents);


echo '<p class="ode"><span onclick="showdiff(\''.$predmet.'\', \''.$uloha.'\')">[diff selected]</span></p>';
foreach($wholelines as $k)
{
if($k == '') continue;
		ob_start();
		
		
			$naostro = true;
			$naostro6b = false;
			if(strstr($k, 'student') !== false)
			{
				$naostro = false;
			}
			$datum = explode(" ", $k, 2);
			$nice = nice_date($datum[0]);
			$first = explode("#", $k, 2);
			$head = explode(":", $first[0]);
			$sum = $head[1];
			$results = nice_tests($k);
			if((strstr($sum, 'points=6') !== false))
					{
						$naostro6b = true;
					}
			$folder = $ruser."_".$datum[0];
			echo '<p class="ode '.($naostro?"yellow":"green").'">'.$nice." <input id='".$folder."' onchange='changeTick(this)' type='checkbox' /> <span class='".((strstr($sum, 'ok;') != false)?"blue":"red")."' onmouseout='med_closeDescription()' onmousemove='med_mouseMoveHandler(event,\"".$results."\")'>".$sum."</span>";
			echo '<span class="cp" onclick=\'showcode("'.$predmet.'","'.$uloha.'","'.$folder.'")\'> [sources]</span></p>';
		
		echo '</div>';
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;

}



?>
