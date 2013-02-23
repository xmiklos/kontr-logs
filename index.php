#!/packages/run/php/bin/php
<?php
include "config.php";

setcookie('lastreload', time());
session_start();

if(isset($_SERVER['REMOTE_USER']))
{
	$userat = $_SERVER['REMOTE_USER'];
	$pieces = explode("@", $userat);
	$login = $pieces[0];
}

if(in_array($login, get_authorized_users()))
{
	$_SESSION['my']=true;
}
else
{
	$_SESSION['my']=false;
}

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
	echo 'Unauthorized access!';
	exit;
}

if( isset($_REQUEST['uloha']) && isset($_REQUEST['predmet']) )
{
	$uloha = $_REQUEST['uloha'];
	$predmet = $_REQUEST['predmet'];
	$_SESSION['uloha']=$uloha;
	$_SESSION['predmet']=$predmet;
}

//system("echo ".$_SERVER['REMOTE_ADDR']." >> access.log");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="jquery-1.3.2.min.js" type="text/javascript" ></script>
	<script src="jquery.scrollTo-1.4.2-min.js" type="text/javascript" ></script>
	<script src="src/ace.js" type="text/javascript" charset="utf-8"></script>
	<script src="src/mode-c_cpp.js" type="text/javascript" charset="utf-8"></script>
	<script src="site.js" type="text/javascript" ></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36929473-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<title>_logs_</title>
</head>
<body id="top" onload='med_init()' onscroll="bodyscroll()" >
<div id="panel_enabler" onmouseover='$("#head").slideDown();'></div>
<div id="head">
(<?php echo $login ?>)
<!--
<form action="index.php" method="GET">
<input type="hidden" name="logout" value="1"/>
<input type="submit" value="Logout"/>
</form>
-->
<div class='opt' style="border: 0">
<form id="vyber" action="index.php" method="GET">
<span>Choose task and subject:</span>
<select name="uloha" onchange="poslat()">
  <option <?php echo ($uloha==""?"selected=\"selected\"":" ") ?> value=""></option>
  <option <?php echo ($uloha=="hw01"?"selected=\"selected\"":" ") ?> value="hw01">hw01</option>
  <option <?php echo ($uloha=="hw02"?"selected=\"selected\"":" ") ?> value="hw02">hw02</option>
  <option <?php echo ($uloha=="hw03"?"selected=\"selected\"":" ") ?> value="hw03">hw03</option>
  <option <?php echo ($uloha=="hw04"?"selected=\"selected\"":" ") ?> value="hw04">hw04</option>
  <option <?php echo ($uloha=="hw05"?"selected=\"selected\"":" ") ?> value="hw05">hw05</option>
  <option <?php echo ($uloha=="hw06"?"selected=\"selected\"":" ") ?> value="hw06">hw06</option>
</select>

<select name="predmet" onchange="poslat()">
  <option <?php echo ($predmet=="pb071"?"selected=\"selected\"":" ") ?> value="pb071">PB071</option>
  <option <?php echo ($predmet=="pb161"?"selected=\"selected\"":" ") ?> value="pb161">PB161</option>
</select>
<?php

if(isset($_REQUEST['uloha']) && isset($_REQUEST['predmet']) )
{
	echo '<a class="vpravo" href="#bottom">[bottom]</a>';
}
else
{
	echo '</form></div></body></html>';
	exit;
}
?>
</form>
</div>


<div class='opt'>
Show students with:
<span onclick='filter("nanecisto")' class='green cp'>[nanecisto only submissions]</span>
<span onclick='filter("naostro")' class='yellow cp'>[naostro submissions points < 6]</span>
<span onclick='filter("naostro6b")' class='yellow cp'>[naostro submissions points=6]</span>
<span onclick='filterNone()' class="cp" >[all]</span>
</div>
<div class='opt'>
Show submissions:
<span onclick='filter_sub("nanecisto")' class='green cp'>[nanecisto only]</span>
<span onclick='filter_sub("naostro")' class='yellow cp'>[naostro only]</span>
<span onclick='filter_sub("all")' class='yellow cp'>[all]</span>
</div>
<div class='opt'>
Options:
<span class="cp" onclick="enable_notif(this)"/>[Enable notifications]</span>
<span class="cp" onclick="show_all(this)"/>[Expand all]</span>
</div>
<div></div>
<div class='opt'>
Legend:
<span class='green'>nanecisto</span>
<span class='yellow'>naostro</span>
<span class='blue'>ok</span>
<span class='red'>errors</span>
</div>
</div>
<div style=" height: 200px;"></div>
<?php

$f = KONTR_NG."_logs_/report.log";
$f1 = file_get_contents($f);
$pieces = explode("\n", $f1);

$mypieces;
$sub = $predmet.' '.$uloha;
$students;

function get_user($line)
{
	$parts = explode(" ", $line, 3);

	return $parts[1];
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

function nice_stat($p, $poc)
{
	echo "<tr class=\"user\"><td ><strong>".$p."</strong></td><td style='text-align: right'>".$poc."</td></tr>";
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

$students=array();

foreach($pieces as $value)
{
	if(strstr($value, $sub))
	{
		$mypieces[] = $value;
		$parts = explode(" ", $value, 3);
		if (!in_array($parts[1], $students))
		{
			$students[] = $parts[1];
		}
	}
}

$userlines;

foreach($mypieces as $line)
{
	$user = get_user($line);
	$index = array_search($user, $students);
	$userlines[$index][] = $line;
}

$naostroa;
$i=0;
$max = 0;
echo '<div id="users">';
$naostroa=array();
$naostro6=array();
foreach($userlines as $l)
{
$o=0;
$n=0;
$user='';

		ob_start();
		echo '<div class="odes" id="'.$students[$i].'">';
		echo '<p class="ode cp"><span onclick="showdiff(\''.$predmet.'\', \''.$uloha.'\')">[diff selected]</span></p>';
		foreach($l as $k)
		{
			$naostro = true;
			$naostro6b = false;
			if(strstr($k, 'student') !== false)
			{
				$n++;
				$naostro = false;
			}
			$datum = explode(" ", $k, 2);
			$nice = nice_date($datum[0]);
			$first = explode("#", $k, 2);
			$head = explode(":", $first[0]);
			$sum = $head[1];
			$results = nice_tests($k);
			if($naostro)
			{
				if(!in_array($students[$i], $naostroa))
				{
					$naostroa[] = $students[$i];
				}
				if(!in_array($students[$i], $naostro6))
				{
					if((strstr($sum, 'points=6') !== false))
					{
						$naostro6[] = $students[$i];
						$naostro6b = true;
					}
				}
			}
			$folder = $students[$i]."_".$datum[0];
			echo '<p class="ode '.($naostro?"yellow":"green").'">'.$nice." <input id='".$folder."' onchange='changeTick(this)' type='checkbox' /> <span class='".((strstr($sum, 'ok;') != false)?"blue":"red")."' onmouseout='med_closeDescription()' onmouseover='med_mouseMoveHandler(event,\"".$results."\")'>".$sum."</span>";
			echo '<span class="cp" onclick=\'showcode("'.$predmet.'","'.$uloha.'","'.$folder.'")\'> [sources]</span></p>';
		}
		$o = count($l) - $n;
		echo '</div>';
		$contents = ob_get_contents();
		ob_end_clean();
		
		if($n > $max) $max = $n;
		echo '<div class="user '.(in_array($students[$i], $naostro6)?"naostro6b":(in_array($students[$i], $naostroa)?"naostro":"nanecisto")).'" id="u_'.$students[$i].'" ><span class=\'std\' onclick="tooogle(\''.$students[$i].'\')">'.$students[$i]." </span><span class='vpravo'><span class='green'>".$n."</span> / <span class='yellow'>".$o."</span></span>";
		echo $contents;
		echo '</div>';
$i++;
}
?>
</div>
<div class="opt" id="bottom" style="border: 0"><a class="vpravo" href="#top">[top]</a></div>
<table class="stat_table">
<?php
nice_stat("Studentov:", count($students));
nice_stat("Naostro:", count($naostroa));
nice_stat("Naostro (points==6):", count($naostro6));
nice_stat("Max. nanecisto:", $max);

?>
</table>
<div id="notifbox"></div>

<div id="dummy"></div>
<div style="text-align: center">Please report bugs to xmiklos@fi.muni.cz</div>
</body>
</html>
