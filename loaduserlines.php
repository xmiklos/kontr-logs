#!/packages/run/php/bin/php
<?php
include "config.php";
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

	return $den.".".$mesiac.".".$rok." - ".$hod.":".$min.":".$sec;
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

function nice_tests($line, &$bonus_bad)
{	
	$str = "<div class=\\\"res\\\" >";
	$chunks = explode("#", $line);
	$prvy = false;
	$bonus_bad = false;
	$error = false;
	foreach($chunks as $chunk)
	{
		if($prvy)
		{
		
			$str = $str."<span onclick=\\\"med_closeDescription(1)\\\" class=\\\"".((strstr($chunk, 'ok') != false)?"blue":"red")."\\\">".$chunk."</span><br />";
			if(strstr($chunk, 'ok') == false && strstr($chunk, 'bonus') == false) $error = true;
			if((!$error && strstr($chunk, 'ok') == false && strstr($chunk, 'bonus') != false)) $bonus_bad=true;
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
$all = $_REQUEST['all'];

include "generator.php";
$stud_tutor = array();
$ucos = array();
get_student_tutor($stud_tutor, $ucos);
$tutor = (array_key_exists($ruser, $stud_tutor)?$stud_tutor[$ruser]:"");

ob_start();
system("grep -G '".$ruser.".*".$sub."' ".KONTR_NG."_logs_/report.log");
$contents = ob_get_contents();
ob_end_clean();

$wholelines = explode("\n", $contents);
$global_naostro = false;
$global_naostro6 = false;

ob_start();
//echo '<p class="ode"><span onclick="showdiff(\''.$predmet.'\', \''.$uloha.'\')">[diff selected]</span></p>';
foreach($wholelines as $k)
{
if($k == '') continue;
		
		
		
			$naostro = true;
			$naostro6b = false;
			if(strstr($k, 'student') !== false)
			{
				$naostro = false;
			}
			if(strstr($k, 'teacher') !== false)
			{
				$global_naostro6 = true;
			}
			$datum = explode(" ", $k, 2);
			$nice = nice_date($datum[0]);
			$first = explode("#", $k, 2);
			$head = explode(":", $first[0]);
			$sum = $head[1];
			$bonus_bad;
			$results = nice_tests($k, $bonus_bad);
			if((strstr($sum, 'points=6') !== false))
					{
						$naostro6b = true;
						$global_naostro6 = true;
					}
			$folder = $ruser."_".$datum[0];
			echo '<p class="ode '.($naostro?"yellow":"green").'">'.$nice." <input id='".$folder."' onchange='changeTick(this)' type='checkbox' /> <span class='".($bonus_bad?"purple":((strstr($sum, 'ok;') != false)?"blue":"red"))."' onmouseout='med_closeDescription()' onmousemove='med_mouseMoveHandler(event,\"".$results."\")' onclick='med_disable_des_hide()'>".$sum."</span>";
			echo '<span class="cp vpravo" onclick=\'showcode("'.$predmet.'","'.$uloha.'","'.$folder.'")\'> [sources]</span></p>';
		
		//echo '</div>';
		

}
$contents = ob_get_contents();
		ob_end_clean();
if($all)
{
	$is = "";
	if(array_key_exists($ruser, $ucos)) $is = "<span class='cp vpravo'><a href='https://is.muni.cz/auth/osoba/".$ucos[$students[$i]]."' target='_blank'>[IS]</a>&nbsp;</span>";
	$filter_class = ($global_naostro6?"naostro6b":(($global_naostro)?"naostro":"nanecisto"));
	echo '<div class="user '.($tutor==""?"notutor":$tutor).' '.$filter_class.'" id="u_'.$ruser.'">';
	echo '<span class="number"></span><span class="std" onclick=\'tooogle("'.$ruser.'")\'>'.$ruser.'</span>'.$is.'<div class="odes" id="'.$ruser.'">';
}
		echo $contents;
if ($all) echo "</div></div>";

?>
