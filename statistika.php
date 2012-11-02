#!/packages/run/php/bin/php
﻿<?php
$file=filesize("config.php");
	if ($file == "0")
	{
	exit("<meta http-equiv=\"refresh\" content=\"1;URL=install.php\">");
	}
 /*
if (!IsSet($_SERVER["PHP_AUTH_USER"]))
  {
  Header("WWW-Authenticate: Basic realm=\"Hesla\"");
  Header("HTTP/1.0 401 Unauthorized");
  echo "Přístup pouze na uživatelské jméno a heslo.";
  exit;
  }
  else
    {
      if ($_SERVER["PHP_AUTH_USER"]!="meno") { echo "Neplatné prihlasovacie meno!"; exit;}
      if ($_SERVER["PHP_AUTH_PW"]!="heslo") { echo "Neplatné heslo!"; exit;} 
    }
*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php $hd= rand(0, 16777215); $cislo=dechex($hd); ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
<title>log subor</title>
  <style type="text/css" >
body {background-image: url(glossymetal.jpg)<?php //echo $cislo; ?>}
</style>
  <link rel="stylesheet" type="text/css" href="style2.css"></link>
<link href="favicon.jpg" rel="shortcut icon" type="image/x-icon" />
<script src="unx.js" type="text/javascript"></script>
</head>
<body onload="start()">
<script type="text/javascript">		
				function getMouseCoords(e) {
					var coords = {};
					if (!e) var e = window.event;
					if (e.pageX || e.pageY) 	{
						coords.x = e.pageX;
						coords.y = e.pageY;
					}
					else if (e.clientX || e.clientY) 	{
						coords.x = e.clientX + document.body.scrollLeft
							+ document.documentElement.scrollLeft;
						coords.y = e.clientY + document.body.scrollTop
							+ document.documentElement.scrollTop;
					}
					return coords;
				}
				
				function med_mouseMoveHandler(e, desc_string){
					var coords = getMouseCoords(e);
					med_showDescription(coords, desc_string);
				}
				
				function med_closeDescription(){
					var layer = document.getElementById("description");
					layer.style.visibility = "hidden";
				}
				
				function init_local(){
					med_init();
				}
				
				function med_init(){
					layer = document.createElement("div");
					layer.style.width = 200 + "px";
					layer.style.position = "absolute";
					layer.style.zIndex = 999;
					layer.style.visibility = "hidden";
					layer.style.backgroundColor = "#FFF";
					layer.style.border = "1px solid #BBB";
					layer.style.padding = "2px 5px";
					layer.id = "description";
					document.body.appendChild(layer);
				}
				
				function med_showDescription(coords, desc_string){
					var layer = document.getElementById("description");
					layer.style.top = (coords.y + 25)+ "px";
					layer.style.left = (coords.x - 20) + "px";
					layer.style.visibility = "visible";
					layer.innerHTML = desc_string;
				}
			   </script>
<div align="center">
<hr /><form method="post" action="<?echo $_SERVER["PHP_SELF"]?>">
<?php

session_register("id");
if ($odoslal)
{
if (($_POST["id"]) == 0)
{
$_SESSION["id"]=0;
}
else
{
$_SESSION["id"]=1;
}
}
if ($_SESSION["id"] == 1)
	
echo "Random colors: <input type=\"submit\" name=\"id\" value=\"disable\" /> <input type=\"hidden\" name=\"id\" value=\"0\" /> <input type=\"hidden\" name=\"odoslal\" value=\"true\" />";

else

	echo "Random colors: <input type=\"submit\" name=\"id\" value=\"enable\" /> <input type=\"hidden\" name=\"id\" value=\"1\" /> <input type=\"hidden\" name=\"odoslal\" value=\"true\" />";

?>
	</form><hr></hr>
<form method="post" action="<?echo $_SERVER["PHP_SELF"]?>">
    Počet štatistík na stránku: <select name="lines" size="1">
<option value="15" <?php if (($_REQUEST[lines]) == 15) echo "selected=\"selected\""; ?>>15</option>
<option value="20" <?php if (($_REQUEST[lines]) == 20) echo "selected=\"selected\""; ?>>20</option>
<option value="50" <?php if (($_REQUEST[lines]) == 50) echo "selected=\"selected\""; ?>>50</option>
<option value="100" <?php if (($_REQUEST[lines]) == 100) echo "selected=\"selected\""; ?>>100</option>
</select>
      <input type="submit" name="Zmeň" value="Zmeň"></input>
    </form>
<?php
if ($_SESSION["id"] == 1)
{
$zobraz=true;
}
else
{
$zobraz=false;
}
if ($zobraz)
	echo "";

	include "config.php";
	
if (!isset($_REQUEST[lines]))
{
	define ("ROWS", 15);
}	
else
{
	define ("ROWS", $_REQUEST[lines]);
}
  if (!isset($_GET["celkom"])) //pokud nevíme, kolik bude záznamů tak to zjistíme...
  {
    $vysledek=mysql_query("select count(*) as pocet from " .SQL_TABLE)or die("Nelze vykonat definiční dotaz: " . mysql_error());
    $zaznam=mysql_fetch_array($vysledek);
    $celkom=$zaznam["pocet"];
  }
  else
  {
      $celkom=$_GET["celkom"];
  }
  if ($celkom>ROWS)
  {
    if (!isset($_GET["od"])) $od=1; else $od=$_GET["od"];
    $vysledek=mysql_query("select * from ".SQL_TABLE." order by id desc"." limit ".($od-1).", ".ROWS);
      echo "Záznamov: ".$od."-";
    echo (($od+ROWS-1)<=$celkom)?($od+ROWS-1):$celkom;
    echo " z celkom $celkom&nbsp;&nbsp;&nbsp;";
      //začátek - vytvoř odkaz pouze pokud nejsme na začátku
       if ($od==1) echo "Začiatok&nbsp;|&nbsp;";
      else echo "<a href=\"".$_SERVER["PHP_SELF"]."?celkom=$celkom&amp;od=1&amp;lines=".ROWS."\">Začiatok</a>&nbsp;|&nbsp;";
      //zpět - vytvoř odkaz pouze pokud nejsme v prvních ROWS
       if ($od<ROWS) echo "Predchádzajúci&nbsp;|&nbsp;";
      else echo "<a href=\"".$_SERVER["PHP_SELF"]."?celkom=$celkom&amp;od=".($od-ROWS)."&amp;lines=".ROWS."\">Predchádzajúci</a>&nbsp;|&nbsp;";
    //další - vytvoř, pouze pokud nejsme v posledních ROWS
       if ($od+ROWS>$celkom) echo "Nasledujúci&nbsp;|&nbsp;";
      else echo "<a href=\"".$_SERVER["PHP_SELF"]."?celkom=$celkom&amp;od=".($od+ROWS)."&amp;lines=".ROWS."\">Nasledujúci</a>&nbsp;|&nbsp;";
    //poslední - to je posledních (zbytek po dělení ROWS) záznamů
       if ($od>$celkom-ROWS) echo "Koniec&nbsp;<br></br>";
      else echo "<a href=\"".$_SERVER["PHP_SELF"]."?celkom=$celkom&amp;od=".($celkom-$celkom%ROWS+1)."&amp;lines=".ROWS."\">Koniec</a><br></br>";
  }
  else
  {
	  $vysledek=mysql_query("select * from ".SQL_TABLE." order by id desc");
  }
    
	echo "<table border='1'>";
	echo "<tr><td align=\"center\">IP</td> <td align=\"center\">Čas</td> <td align=\"center\">Dátum</td></tr>";
	while ($zaznam=MySQL_Fetch_Array($vysledek))
	{
	if (($zaznam[4]) == "")
	{
		$zaznam[4] = "Žiadné info";
	}
	if  (ereg("^66\.249.*$", $zaznam[1]))
	{
	if ($zobraz)
	{
	$hd2= rand(0, 16777215); $cislo2=dechex($hd2);
	$hd3= rand(0, 16777215); $cislo3=dechex($hd3);
	$hd4= rand(0, 16777215); $cislo4=dechex($hd4);
	}
	echo "<tr><td align=\"center\" ><span onmouseout=\"med_closeDescription()\" onmousemove=\"med_mouseMoveHandler(arguments[0],'$zaznam[4]')\"><a href='http://whois.domaintools.com/$zaznam[1]'>Googlebot</a></span></td> <td align=\"center\" >$zaznam[3]</td> <td align=\"center\" > $zaznam[2]</td></tr>";
	}
	else	
	{
	if ($zobraz)
	{	
	$hd2= rand(0, 16777215); $cislo2=dechex($hd2);
	$hd3= rand(0, 16777215); $cislo3=dechex($hd3);
	$hd4= rand(0, 16777215); $cislo4=dechex($hd4);
	}
	echo "<tr><td align=\"center\" ><span onmouseout=\"med_closeDescription()\" onmousemove=\"med_mouseMoveHandler(arguments[0],'$zaznam[4]')\"><a href='http://whois.domaintools.com/$zaznam[1]'>$zaznam[1]</a></span></td> <td align=\"center\" >$zaznam[3]</td> <td align=\"center\" >$zaznam[2]</td></tr>";
	}
	}
	echo "</table>";
		
?>

</div>
<hr></hr>
<div align="center">
&copy; <a href="mailto:michall@mail.com">Walker</a>, 2009<br></br> 
</div>
</body>
</html>
