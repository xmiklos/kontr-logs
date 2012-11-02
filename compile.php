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

$flags = $_REQUEST['flags'];

if($_SESSION['predmet'] == 'pb161')
echo shell_exec("/usr/bin/g++ -ansi -pedantic -Wall -Werror ".$flags." ".$_REQUEST['files']." -o binfile 2> errors.out");
else
echo shell_exec("/usr/bin/gcc -std=c99 -pedantic -Wall -Werror ".$flags." ".$_REQUEST['files']." -o binfile 2> errors.out");
	
	$f1 = file_get_contents('errors.out');
	$output = str_replace("\n", '<br />', $f1);
	
	if($output == '') echo 'oK!';
	else echo $output;


?>
