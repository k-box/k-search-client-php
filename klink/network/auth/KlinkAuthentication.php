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
	 * Tag info to be used when selecting the core for the communication
	 * @var string
	 */
	private $tag;

	/**
	 * API Version to be used when selecting the core for the communication
     *
	 * @var string
	 */
	private $apiVersion;


    /**
     * Creates a new KlinkAuthentication
     *
     * @param string $core_url   the URL of the Core instance
     * @param string $username   the username that must be used for authentication
     * @param string $password   the password
     * @param string $tag        the visibility tag to attach to this core instance. Default \KlinkVisibilityType::KLINK_PRIVATE. Will be used in core selection based on the request to be executed.
     * @param string $apiVersion specify the version of the KCore API to use
     */
	function __construct($core_url, $username, $password, $tag = \KlinkVisibilityType::KLINK_PRIVATE, $apiVersion = KlinkCoreClient::DEFAULT_KCORE_API_VERSION)
	{
		KlinkHelpers::is_valid_url( $core_url, 'core url');
		KlinkHelpers::is_string_and_not_empty( $username, 'username');
		KlinkHelpers::is_string_and_not_empty( $password, 'password');
		KlinkHelpers::is_string_and_not_empty( $tag, 'tag');

        $this->apiVersion = $apiVersion;
		$this->core = $core_url;
		$this->username = $username;
		$this->password = $password;
		$this->tag = $tag;
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
	
	/**
	 * Return the tag assigned to this Core authentication.
	 * @return string the tag, if no tag was specified the default value is \KlinkVisibilityType::KLINK_PUBLIC
	 */
	public function getTag(){
		return $this->tag;
	}

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }
}
