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

	return $den.".".$mesiac." - ".$hod.":".$min.":".$sec;
}

$uloha = $_SESSION['uloha'];
$predmet = $_SESSION['predmet'];
$sub = $predmet.' '.$uloha;
$timesince = $_REQUEST['cas'];

$f = KONTR_NG."_logs_/report.log";
$f1 = file_get_contents($f);
$wholelines = explode("\n", $f1);

foreach($wholelines as $wholeline)
{
	if(strstr($wholeline, $sub))
	{
		$parts = explode(" ", $wholeline, 3);
		if($timesince >= get_time($parts[0]))
		{
			continue;
		}
		echo '<div class="notif cp" title="'.$parts[0].'"><a  onclick="update_user(\''.$parts[1].'\')">';
		echo nice_date($parts[0]);
		echo ' '.$parts[1];
		echo '</a></div>';
	}
}


?>
