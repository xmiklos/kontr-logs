#!/packages/run/php/bin/php
<?php
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

function recDir($path, &$fnames, &$fpaths, $relpath, &$relpatharray)
{
	$hand=opendir($path);

	while (($fil = readdir($hand)))
	{
		if($fil == '.' || $fil == '..'){
			continue;
		}
		if(is_dir($path.$fil))
		{
			recDir($path.$fil.'/', $fnames, $fpaths, $relpath.'/'.$fil, $relpatharray);
			continue;
		}
		if(((strstr($fil, '.h') !== false) || (strstr($fil, '.c') !== false)) && (strstr($fil, '.html') === false)) 
		{
			if(!in_array($fil, $fnames))
			{
				$fnames[] = $fil;
				$fpaths[] = $path.$fil;
				$relpatharray[] = $relpath.'/'.$fil;
 			}
		}
	}

	closedir($hand);
}

function get_index($value, $array)
{
	for($i=0; $i<count($array); $i++)
	{
		if($array[$i] == $value)
		{
			return $i;
		}
	}
}
/*
function findDir($path)
{
	$hand=opendir($path);
	$ret='';
	$read = true;
	while (($fil = readdir($hand)) && $read)
	{
		if(!is_dir($path.'/'.$fil) || $fil == '.' || $fil == '..'|| (strstr($fil, 'naostro') !== false))
		{
			continue;
		}
		$ret = $fil;
		$read = false;
	}
	if($ret=='') echo 'Something is wrong!';
	closedir($hand);
	return $ret;
}
*/
$s = "/";
$f = "/home/xtoth1/kontrNG/_tmp_/";
$mena1;
$cesty1;
$mena2;
$cesty2;
$ra1;
$ra2;
$empty="";

$p1 = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['odevzdani'].'/';
$p2 = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['odevzdani2'].'/';

recDir($p1, $mena1, $cesty1, $empty, $ra1);
recDir($p2, $mena2, $cesty2, $empty, $ra2);
#$fo = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['odevzdani'].$s.$test_folder.'/';
#$fo2 = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['odevzdani2'].$s.$test_folder.'/';

if(count($mena1) < count($mena2))
{
	$mena = $mena1;
	$cesty = $cesty1;
	$other = $cesty2;
	$other2 = $mena2;
	$ra = $ra1;
}
else
{
	$mena = $mena2;
	$cesty = $cesty2;
	$other = $cesty1;
	$other2 = $mena1;
	$ra = $ra2;
}

echo "<div style='margin-left: 30px; font-size: 15px;'>";
#$hand=opendir($fo);
$projectContents = '';

	for($i=0; $i < count($mena); $i++)
	{
			ob_start();
			system("diff ".$cesty[$i]." ".$other[get_index($mena[$i], $other2)], $ret);
			ob_end_clean();
			$relat = strstr($ra[$i], '/');
			$projectContents .= '<li  onclick="showdiffout(\''.$_REQUEST['predmet'].'\',\''.$_REQUEST['uloha'].'\',\''.$relat.'\')"><span class="cp">'.$mena[$i].' '.(($ret!=0)?"(difference)":"").'</span></li>';
	}

if(count($mena1) == 0) echo 'Directory is empty, no source files found!';

echo $projectContents;
echo "</div>";

?>

