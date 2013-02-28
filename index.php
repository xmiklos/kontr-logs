#!/packages/run/php/bin/php
<?php

if($_SERVER['SERVER_PORT'] != 443) {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
   exit();
}

include "config.php";

setcookie('lastreload', time());
session_start();
setcookie('PHPSESSIDSHA', sha1(session_id()));

if(isset($_SERVER['REMOTE_USER']))
{
	$userat = $_SERVER['REMOTE_USER'];
	$pieces = explode("@", $userat);
	$login = $pieces[0];
}
else
{
	echo 'Unauthorized access!';
	exit;
}

$ip = $_SERVER["REMOTE_ADDR"];
$date = date("d. m. Y H:i:s");
$ua = $_SERVER['HTTP_USER_AGENT'];

if((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
	if(in_array($login, get_authorized_users()))
	{
		$_SESSION['my']=true;
		system("echo '$login $ip $date $ua' >> authorized.log");
	}
	else
	{
		$_SESSION['my']=false;
	}
}

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
	echo 'Unauthorized access!';
	system("echo '$login $ip $date $ua' >> unauthorized.log");
	exit;
}

if( isset($_REQUEST['uloha']) && isset($_REQUEST['predmet']) )
{
	$uloha = $_REQUEST['uloha'];
	$predmet = $_REQUEST['predmet'];
	$_SESSION['uloha']=$uloha;
	$_SESSION['predmet']=$predmet;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script src="jquery-1.9.1.min.js" type="text/javascript" ></script>
	<script src="jquery.scrollTo-1.4.3.1-min.js" type="text/javascript" ></script>
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
<body id="top" onload='med_init(); number_users(); clear_cookies(); ' onscroll="bodyscroll()" >
<div id="panel_enabler" onmouseover='$("#head").css( "position","fixed" ); $("#space_filler").show();'></div>
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
<?php
	if (!isset($predmet)) $predmet=DEFAULT_SUBJECT;
	foreach(get_tasks($predmet) as $task)
	{
		echo '<option '.(isset($uloha) && $uloha==$task?"selected=\"selected\"":" ").' value="'.$task.'">'.$task.'</option>';
	}

?>
</select>

<select name="predmet" onchange="poslat()">
  <option <?php echo (isset($predmet) && $predmet=="pb071"?"selected=\"selected\"":" ") ?> value="pb071">PB071</option>
  <option <?php echo (isset($predmet) && $predmet=="pb161"?"selected=\"selected\"":" ") ?> value="pb161">PB161</option>
</select>
 <input type="submit" value="GO">
<?php

if(isset($_REQUEST['uloha']) && isset($_REQUEST['predmet']) )
{
	echo '<a class="vpravo" href="#bottom">[bottom]</a><a class="vpravo" href="#top">[top]</a>';
}
else
{
	echo '</form></div></body></html>';
	exit;
}

include "generator.php";
$stud_tutor = array();
$ucos = array();
get_student_tutor($stud_tutor, $ucos);

$tutors = array_unique(array_values($stud_tutor));
?>
</form>
</div>


<div class='opt'>
<form>
Show students with:
<select id="studfilter" onchange="filter()">
  <option  value="all">all</option>
  <option  value="nanecisto">nanecisto only submissions</option>
  <option  value="naostro">naostro submissions points &lt; 6</option>
  <option  value="naostro6b">naostro submissions points=6</option>
</select>
Seminar tutor:
<select id="tutorfilter" onchange="filter()">
<option  value="all">all</option>
<?php
	foreach($tutors as $tutor)
	{
		if($tutor=="")
		{
			echo '<option value="notutor">No tutor</option>';
		}
		else
		{
			echo '<option value="'.$tutor.'">'.$tutor.'</option>';
		}
	}
?>
</select>
</form>
</div>
<div class='opt'>
<form>
Show submissions:
<select onchange="filter_sub(this.value)">
  <option  value="all">all</option>
  <option  value="nanecisto">nanecisto only</option>
  <option  value="naostro">naostro only</option>
</select>
</form>
</div>
<div class='opt'>
Options:
<span class="cp" onclick="enable_notif(this)"/>[Enable notifications]</span>
<span class="cp" onclick="show_all(this)"/>[Expand all]</span>
<span class="cp" onclick="showdiff('<?php echo $predmet; ?>', '<?php echo $uloha; ?>')">[diff selected submissions]</span>
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
	echo "<tr><td ><strong>".$p."</strong></td><td style='text-align: right'>".$poc."</td></tr>";
}

function nice_tests($line, &$bonus_bad, &$error)
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
		
		foreach($l as $k)
		{
			$naostro = true;
			$naostro6b = false;
			if(strstr($k, 'student') !== false)
			{
				$n++;
				$naostro = false;
			}
			$datum = explode(" ", $k, 6);
			$nice = nice_date($datum[0]);
			$first = explode("#", $k, 2);
			$head = explode(":", $first[0]);
			$sum = $head[1];
			$bonus_bad; $error;
			$results = nice_tests($k, $bonus_bad, $error);
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
			echo '<p class="ode '.($naostro?"yellow":"green").'">'.$nice." <input id='".$folder."' onchange='changeTick(this)' type='checkbox' /> <span class='".($bonus_bad?"purple":(!$error?"blue":"red"))."' onmouseout='med_closeDescription()' onmouseover='med_mouseMoveHandler(event,\"".$results."\")' onclick='med_disable_des_hide()'>".$sum."</span>";
			echo '<span class="cp vpravo" onclick=\'showcode("'.$predmet.'","'.($uloha==""?$datum[4]:$uloha).'","'.$folder.'")\'> [sources]</span></p>';
		}
		$o = count($l) - $n;
		echo '</div>';
		$contents = ob_get_contents();
		ob_end_clean();
		
		if($n > $max) $max = $n;
		$tu = (array_key_exists($students[$i], $stud_tutor)?$stud_tutor[$students[$i]]:"");
		$is = "";
		if(array_key_exists($students[$i], $ucos)) $is = "<span class='cp vpravo'><a href='https://is.muni.cz/auth/osoba/".$ucos[$students[$i]]."' target='_blank'>[IS]</a>&nbsp;</span>";
		echo '<div class="user '.($tu==""?"notutor":$tu).' '.(in_array($students[$i], $naostro6)?"naostro6b":(in_array($students[$i], $naostroa)?"naostro":"nanecisto")).'" id="u_'.$students[$i].'" ><span class="number"></span><span class=\'std\' onclick="tooogle(\''.$students[$i].'\')">'.$students[$i]." </span><span class='vpravo'><span class='green'>".$n."</span> / <span class='yellow'>".$o."</span></span>".$is;
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
<div style="text-align: center">Please send feedback and bug reports to xmiklos@fi.muni.cz</div>
</body>
</html>
