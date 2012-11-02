#!/packages/run/php/bin/php
<?php
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

$file = $_REQUEST['subor'];

$f = "/home/xtoth1/kontrNG/_tmp_/";
$s = '/';
$f1 = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['d1'].$file;
$f2 = $f.$_REQUEST['predmet'].$s.$_REQUEST['uloha'].$s.$_REQUEST['d2'].$file;

echo $f2.'<br />';
echo $f1.'<br />';

ob_start();
system("diff ".$f2." ".$f1, $ret);
$contents = ob_get_contents();
ob_end_clean();

echo str_replace("\n", '<br />', $contents);

if($ret == 0) echo 'There is no diffrence';

?>
