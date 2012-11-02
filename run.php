#!/packages/run/php/bin/php
<?php
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

chdir("tmp/".session_id());
system("rm errors.out");

$input = $_REQUEST['inputfile'];
$grind = $_REQUEST['valgrind'];
$args = $_REQUEST['args'];

$valgrind='';
$inputfile='';
if($grind != 'false') $valgrind = 'valgrind --leak-check=full ';
if($input != '') $inputfile = ' < '.$input;


shell_exec($valgrind."./binfile > stdout.out 2> errors.out".$inputfile." ".$args);
	$stdout = file_get_contents('stdout.out');
	$stdout  = str_replace("\n", '<br />', $stdout);
	$f1 = file_get_contents('errors.out');
	$output = str_replace("\n", '<br />', $f1);
	
	echo $stdout;
	if($output != '' ) echo 'stderr:<br />'.$output;


?>
