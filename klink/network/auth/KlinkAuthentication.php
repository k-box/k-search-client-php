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
	 * core url
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
	 * @throws IllegalArgumentException if any of the parameters is wrong
	 */
	function __construct($core_url, $username, $password)
	{

		KlinkHelpers::is_valid_url( $core_url, 'core url');

		KlinkHelpers::is_string_and_not_empty( $username, 'username');

		KlinkHelpers::is_string_and_not_empty( $password, 'password');


		$this->core = $core_url;
		$this->username = $username;
		$this->password = $password;
	}


	

	/**
	 * The Core Url
	 * @return string
	 */
	public function getCore() {
		return $this->core;
	}

	/**
	 * The username used for authentication
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * The password used for authentication
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

}