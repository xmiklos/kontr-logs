<?php
session_start();

if ((!isset($_SESSION['noted'])) || (!$_SESSION['noted']))
{
	include "config.php";
	$browser = get_browser(null, true);
	mysql_query ("insert into ".SQL_TABLE." (ip, datum, cas, agent, browser) values('".$_SERVER["REMOTE_ADDR"]."', '".date("d. m. Y")."', '".date("H:i:s")."', '".$_SERVER['HTTP_USER_AGENT']."', '".$browser."');")or die("Nelze vykonat definièní dotaz: " . mysql_error());

$_SESSION['noted'] = true;
}

?>
