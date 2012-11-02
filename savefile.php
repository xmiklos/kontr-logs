#!/packages/run/php/bin/php
<?php
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

$file = $_REQUEST['subor'];
$data = $_REQUEST['data'];

//$data = str_replace('&nbsp;', ' ', $data);

$f1 = file_put_contents($file, $data);

echo ($f1===false?"error":"");

?>
