#!/packages/run/php/bin/php
<?php
include "config.php";
session_start(); 

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
echo 'logged out';
exit;
}

system("rm -rf tmp/".session_id());

?>
