<?php

if ((!isset($_SESSION['my'])) || (!$_SESSION['my']))
{
	echo 'logged out';
	exit;
}

function get_student_tutor()
{
$f1 = file_get_contents(KONTR_STUDENTS);

$pieces = explode("\n", $f1);
$students =array();

foreach($pieces as $value)
{

	$parts = explode(",", $value);
	if(count($parts) != 6) continue;
	
	$tutor = $parts[5];
	$student = $parts[0];
	
	$students[$student] = $tutor;
}

return $students;

}


?>
