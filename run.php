#!/packages/run/php/bin/php
<?php
include "config.php";
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

chdir("tmp/".sha1(session_id()));
system("rm errors.out");

$input = $_REQUEST['inputfile'];
$grind = $_REQUEST['valgrind'];
$args = $_REQUEST['args'];

$valgrind='';
$inputfile='';
if($grind != 'false') $valgrind = 'valgrind --leak-check=full ';
if($input != '') $inputfile = ' < '.$input;
$stdout="";

shell_exec($valgrind."./binfile > stdout.out 2> errors.out".$inputfile." ".$args." & \n echo $! > process.id");
	$ret=0;
	$counter=0;
	while(!$ret)
	{
		system("ps -p `cat process.id` &> /dev/null", $ret);
		sleep(1);
		$counter++;
		if($counter > 60)
		{
			system("kill -9 `cat process.id`");
			$stdout="<strong>Beh programu bol nasilne ukonceny. Doba behu prekrocila povoleny limit.</strong><br />";
			break;
		}
	}

	$stdout .= htmlentities(file_get_contents('stdout.out'));
	$stdout  = str_replace("\n", '<br />', $stdout);
	$f1 = htmlentities(file_get_contents('errors.out'));
	$output = str_replace("\n", '<br />', $f1);
	
	echo $stdout;
	if($output != '' ) echo 'stderr:<br />'.$output;


?>
