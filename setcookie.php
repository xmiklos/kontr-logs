#!/packages/run/php/bin/php
<?php
include "config.php";
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
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

$datum = $_REQUEST['datum'];

setcookie('lastreload', get_time($datum)+1);
?>
