
<?php
require_once "Auth.php";

class KerberosAuth extends Auth
{
	private function get_authorized_users()
	{
		$output = `getent group pb161 pb071`; // load command from config.ini
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
				$this->set_username($login);
				//system("echo '$login $ip $date $ua' >> authorized.log"); // to do config
				return;
			}
			else
			{
				//system("echo '$login $ip $date $ua' >> unauthorized.log"); // to do config
			}
		}
		
		echo 'Unauthorized access!'; // to do better output
		exit;
		
	}
	
}

?>
