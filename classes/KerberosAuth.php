<?php
/**
 * KerberosAuth class
 * @package
 */

require_once "Auth.php";

/**
 * Method implements abstract method of Auth class for autentication and authorization
 */
class KerberosAuth extends Auth
{
        /**
         * Method returns array of user login that are authorized to use the application
         * Uses system call to retreive user list from unix groups
         * 
         * @return array
         */
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
        
        /**
         * Method gets user login and compares it to list of authorized users
         * If it matches, than it sets logged attribute in super class to true
         */
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
