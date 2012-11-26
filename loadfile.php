#!/packages/run/php/bin/php
<?php
include "config.php";
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

$file = $_REQUEST['subor'];

$f1 = file_get_contents($file);

$name = strrchr($file, '/');

if($name == '/student_email' || $name == '/teacher_email')
{
	$f1 = str_replace("\n", '<br />', $f1);
}

if ((isset($_REQUEST['strip'])) || ($_REQUEST['strip']))
{
	//$f1 = str_replace(' ', '&nbsp;', $f1);
}

echo $f1;

?>
