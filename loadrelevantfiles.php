#!/packages/run/php/bin/php
<?php
include "config.php";
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

function recDir($path, &$fnames, &$fpaths, &$sha)
{
$LOAD_FILE_TYPES = array('.c', '.h', '.in', '.out');

	$hand=opendir($path);

	while (($fil = readdir($hand)))
	{
		if($fil == '.' || $fil == '..'){
			continue;
		}
		if(is_dir($path.$fil))
		{
			recDir($path.$fil.'/', $fnames, $fpaths, $sha);
			continue;
		}
		foreach($LOAD_FILE_TYPES as $type)
		{
			if((strstr($fil, $type) !== false) && (strstr($fil, 'grind') === false) && (strstr($fil, '.html') === false)) 
			{
				$sh = sha1_file($path.$fil);
				if(!in_array($sh, $sha) || !in_array($fil, $fnames))
				{
					$fnames[] = $fil;
					$fpaths[] = $path.$fil;
					$sha[] = $sh;
				}
				
			}
		}
	}

	closedir($hand);
}

$s = "/";
$f = KONTR_NG."_tmp_/";
$begin = $f.$_SESSION['predmet'].$s.$_SESSION['uloha'].$s.$_REQUEST['odevzdani'].$s;

$mena;
$cesty;
$filestocompile='';

recDir($begin, $mena, $cesty);
system("mkdir tmp 2> /dev/null");
system("rm -rf tmp/".sha1(session_id()));
system("mkdir tmp/".sha1(session_id()));


ob_start();
echo '<ul id="sourceslist">';
for($i = 0; $i < count($mena); $i++)
{
	$tocompile=false;
	if((strstr($mena[$i], '.c') !== false) && (strstr($mena[$i], 'main') === false))
	{
		$tocompile = true;
		$filestocompile .= " ".$mena[$i];
	}
	$prefix = 1;
	$name;
	$newname = $mena[$i];
	do
	{	
		system("ls ./tmp/".sha1(session_id())."/".$newname." > /dev/null", $ret);
		$name = $newname;
		$newname = $prefix."_".$mena[$i];
		$prefix++;
	}
	while($ret == 0);
	system("cp ".$cesty[$i]." ./tmp/".sha1(session_id())."/".$name);
	echo '<li><input type="checkbox" onchange="inex_cmpil_file(this)" '.($tocompile?"checked":"").' value="'.$name.'" /> <span class="cp" onclick="edit(\''.$name.'\')">'.$name.'</span></li>';
}
echo '</ul>';
$contents = ob_get_contents();
ob_end_clean();

setrawcookie('compile_files', rawurlencode($filestocompile));

echo $contents;

?>

