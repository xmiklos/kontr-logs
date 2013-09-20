<?php
/**
 * Auth class
 * @package
 */

/**
 * Abstract class Auth provides methods for implementation 
 * of different ways of authentication and authorization
 */
abstract class Auth
{
        /**
         * user username
         * @var string
         */
	private static $username;
        
        /**
         * Indicates whether user is logged
         * @var boolean
         */
	private static $logged = false;
	
        /**
         * Constructor
         */
	final function __construct() {}
	
        /**
         * Method gets username
         */
	static function get_username()
	{
		return self::$username;
	}
	
        /**
         * Static method determines that authentication is enables and in that case
         * creates new instance of subclass specified in configuration.
         * calls method login and checks whether user is logged
         */
	static function try_authenticate()
	{
		$auth_class = Config::get_setting("authentication");
		
		if($auth_class)
		{
			require_once "classes/{$auth_class}.php";
			$ref = new ReflectionClass($auth_class);
			$auth = $ref->newInstance();
			$auth->login();
			
			$ip = $_SERVER["REMOTE_ADDR"];
            $date = date("d. m. Y H:i:s");
            $ua = $_SERVER['HTTP_USER_AGENT'];
            $login = self::$username;
			
			if($auth->is_logged() === false)
			{
				echo "Unauthorized access!";
				system("echo '$login $ip $date $ua' >> unauthorized.log");
				exit;
			}
			
			system("echo '$login $ip $date $ua' >> authorized.log");
		}
	}
	
        /**
         * Sets username of logged user
         * 
         * @param string $name
         */
	protected function set_username($name)
	{
		self::$username = $name;
	}
	
        /**
         * Sets state that indicates that user is logged in/out
         * 
         * @param bool $state
         */
	protected function set_logged($state)
	{
		self::$logged = $state;
	}
	
        /**
         * Return whether user is logged or not
         * 
         * @return bool
         */
	function is_logged()
	{
		return self::$logged;
	}
	
        /**
         * Abstract method used by subclasses for logging in
         */
	abstract function login();
	
}

?>
