<?php

/**
 * KlinkAuthentication defines the authentication information for a specific Klink Core Instance. 
 *
 *
 * @package Klink
 * @subpackage Network\Auth
 * @since 0.1.0
 */

/**
 * KlinkAuthentication defines the authentication information for a specific Klink Core Instance.
 *
 * This class defines the information needed for authenticating the Klink Adapter with a KlinkCore instance
 *
 *
 * @package Klink
 * @subpackage Network\Auth
 * @since 0.1.0
 */
final class KlinkAuthentication
{
	
	/**
	 * core
	 * @var string
	 */

	private $core;

	/**
	 * username
	 * @var string
	 */

	private $username;

	/**
	 * password
	 * @var string
	 */

	private $password;


	/**
	 * Creates a new KlinkAuthentication
	 * @param string $core_url the URL of the Core instance
	 * @param string $username the username that must be used for authentication
	 * @param string $password the password
	 * @return KlinkAuthentication
	 */
	function __construct($core_url, $username, $password)
	{
		$this->core = $core_url;
		$this->username = $username;
		$this->password = $password;
	}


	

	/**
	 * getCore
	 * @return string
	 */
	public function getCore() {
		return $this->core;
	}

	/**
	 * getUsername
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * getPassword
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

}