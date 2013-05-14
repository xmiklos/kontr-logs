<?php
require_once "Auth.php";

class KerberosAuth extends Auth
{
	private function get_authorized_users()
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

	function login()
	{
		if(isset($_SERVER['REMOTE_USER']))
		{
			$userat = $_SERVER['REMOTE_USER'];
			$pieces = explode("@", $userat);
			$login = $pieces[0];

			if(in_array($login, $this->get_authorized_users()))
			{
				$this->set_logged(true);
				$this->set_username($login);
				return;
			}
			else
			{
				$this->set_logged(false);
			}
		}
		else
		{
			$this->set_logged(false);
		}
		
	}
	
}

?>
