#!/packages/run/php/bin/php
<?php
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

$s = "/";
$f = "/home/xtoth1/kontrNG/_tmp_/";

$fo = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['odevzdani'].$s;


echo "<div style='margin-left: 30px; font-size: 15px;'>";
$handle=opendir($fo);
$projectContents = '';
$nofiles = true;
$diff = false;
if(isset($_REQUEST['diff'])) $diff=true;

while ($file = readdir($handle)) 
{
	$nofiles = false;
	if (!is_dir($fo.'/'.$file)) 
	{
		if(!$diff) $projectContents .= '<li  onclick="showfile(\''.$fo.$file.'\')"><span class="cp">'.$file.'</span></li>';
		else $projectContents .= '<li  onclick="showdiffout(\''.$_REQUEST['predmet'].'\',\''.$_REQUEST['uloha'].'\',\''.$file.'\')"><span class="cp">'.$file.'</span></li>';
	}
}
closedir($handle);

if($nofiles) echo 'Directory is empty, no source files found!';

echo $projectContents;
echo "</div>";

?>

