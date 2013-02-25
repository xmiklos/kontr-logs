<?php

define("KONTR_NG", "/home/xtoth1/kontrNG/"); // required to end with forward slash character
define("KONTR_STUDENTS", "/home/xtoth1/students.dat"); // required to end with forward slash character

define("GPP", "/usr/bin/g++ -ansi -pedantic -Wall -Werror"); // full path to all binaries required
define("GCC", "/usr/bin/gcc -std=c99 -pedantic -Wall -Werror");

function get_authorized_users()
{
	$output = `getent group pb161 pb071`;
	$pieces = explode("\n", $output);
	$return_array=array();

	foreach($pieces as $group)
	{
		$users = substr(strrchr($group, ":"), 1);
		$users_array = explode(",", $users);
		$return_array = array_merge($return_array, $users_array);
	}
	
	return $return_array;
}

?>
