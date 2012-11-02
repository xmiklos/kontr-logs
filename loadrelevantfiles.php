#!/packages/run/php/bin/php
<?php
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

function recDir($path, &$fnames, &$fpaths)
{
	$hand=opendir($path);

	while (($fil = readdir($hand)))
	{
		if($fil == '.' || $fil == '..'){
			continue;
		}
		if(is_dir($path.$fil))
		{
			recDir($path.$fil.'/', $fnames, $fpaths);
			continue;
		}
		if(((strstr($fil, '.h') !== false) || (strstr($fil, '.c') !== false)) && (strstr($fil, '.html') === false)) 
		{
			if(!in_array($fil, $fnames))
			{
				$fnames[] = $fil;
				$fpaths[] = $path.$fil;
			}
		}
	}

	closedir($hand);
}

$s = "/";
$f = "/home/xtoth1/kontrNG/_tmp_/";
$begin = $f.$_SESSION['predmet'].$s.$_SESSION['uloha'].$s.$_REQUEST['odevzdani'].$s;

$mena;
$cesty;
$filestocompile='';

recDir($begin, $mena, $cesty);

system("mkdir tmp/".session_id());

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
	system("cp ".$cesty[$i]." ./tmp/".session_id()."/".$mena[$i]);
	echo '<li><input type="checkbox" onchange="inex_cmpil_file(this)" '.($tocompile?"checked":"").' value="'.$mena[$i].'" /> <span class="cp" onclick="edit(\''.$mena[$i].'\')">'.$mena[$i].'</span></li>';
}
echo '</ul>';
$contents = ob_get_contents();
ob_end_clean();

setrawcookie('compile_files', rawurlencode($filestocompile));

echo $contents;

?>

