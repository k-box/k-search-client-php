<?php

/**
 * RestClient adapter to mask the underlying library used. 
 *
 * Standardizes the communication with a RESTFul endpoint. Exposes shortcut methods for common operations
 * Simple and uniform REESTful web service client.
 * 
 * Remember to setup the default time zone (e.g., date_default_timezone_set('America/Los_Angeles');)
 *
 *
 * @package Klink
 * @subpackage Network
 * @since 0.1.0
 */

/**
 * Klink RestClient Class for communicating with a RESTful web service.
 *
 * This class is used to consistently make outgoing HTTP requests easy for developers
 * while still being compatible with the many PHP configurations under which
 * Klink Adpter runs.
 * 
 * Remember to setup the default time zone (e.g., date_default_timezone_set('America/Los_Angeles');)
 *
 *
 * @package Klink
 * @subpackage Network
 * @since 0.1.0
 */
final class KlinkRestClient implements INetworkTransport
{

	const JSON_ENCODING = 'application/json';

	/**
	* The common part of the API URL to call
	*/
	private $baseApiUrl = null;

	/**
	* the real transport layer used
	* @var KlinkRestClient
	*/
	private $rest = null;

	/**
	 * Holds the configuration for this instance
	 * @var array
	 */
	private $config = null;

	/**
	 * 
	 * @var JsonMapper
	 */
	private $jm = null;


	/**
	 * Description
	 * @param string $baseApiUrl the starting part of the API url
	 * @param KlinkAuthentication $authentication the authentication information
	 * @param array $options 
	 * @return KlinkRestClient
	 */
	function __construct($baseApiUrl, KlinkAuthentication $authentication, array $options = array())
	{
		$defaults = array(
			/**
			 * Filter the timeout value for an HTTP request.
			 *
			 * @since 0.1.0
			 *
			 * @param int $timeout_value Time in seconds until a request times out.
			 *                           Default 5.
			 */
			'timeout' => 5,
			/**
			 * Filter the number of redirects allowed during an HTTP request.
			 *
			 * @since 0.1.0
			 *
			 * @param int $redirect_count Number of redirects allowed. Fixed to 2, no more than two redirect are allowed
			 */
			'redirection' => 2,
			/**
			 * Filter the user agent value sent with an HTTP request.
			 *
			 * @since 0.1.0
			 *
			 * @param string $user_agent WordPress user agent string.
			 */
			'user-agent' => 'Klink/any;',
			/**
			 * Filter whether to pass URLs through http_validate_url() in an HTTP request.
			 *
			 * @since 0.1.0
			 *
			 * @param bool $pass_url Whether to pass URLs through http_validate_url().
			 *                       Default false.
			 */
			// 'reject_unsafe_urls' => false,
			/**
			 * Enable debug messaging.
			 *
			 * @since 0.1.0
			 *
			 * @param bool $debug Whether to enable debug messages on the log.
			 *                       Default false.
			 */
			'debug' => false,
		);

		$this->config = array_merge($defaults, $options);

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;


		$this->baseApiUrl = $baseApiUrl;

		$this->rest = new KlinkHttp('http://localhost/');
	}


	/**
	 * Description
	 * @param string $url 
	 * @param array $params 
	 * @param null|boolean|string expectedClass what class should the response return, null use a plain array, false expects nothing, otherwise the class name with full namespace
	 * @return type
	 */
	function get( $url, $expected_return_type, array $params = null ){

		/**
		TODO: handle get parameters
		*/


		if(!self::_check_expected_return_type($expected_return_type)){
			return new KlinkError('class_expected', KlinkHelpers::localize('The specified return type is not a class.'));
		}


		$url = self::_construct_url($this->baseApiUrl, $url);

		$result = $this->rest->get( $url );

		if(KlinkHelpers::is_error($result)){
			return $result;
		}


		//204 no content
		//201 created
		//202 accepted

		if( $result['response']['code'] !== 200 ){
			return new KlinkError('http_request_failed', KlinkHelpers::localize($result['response']['message']));
		}

		if($result['headers']['content-type'] !== self::JSON_ENCODING){
			return new KlinkError('http_response_format', KlinkHelpers::localize('Unsupported content encoding from the server.'));
		}

		// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

		$class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

		return self::_deserialize_single($result['body'], $class);
		
	}

	public function getCollection( $url, $expected_return_type, array $params = null )
	{
		/**
		TODO: handle get parameters
		*/
		
		if(!self::_check_expected_return_type($expected_return_type)){
			return new KlinkError('class_expected', KlinkHelpers::localize('The specified return type is not a class.'));
		}


		$url = self::_construct_url($this->baseApiUrl, $url);

		$result = $this->rest->get( $url );

		if(Helpers::is_error($result)){
			return $result;
		}


		//204 no content
		//201 created
		//202 accepted

		if( $result['response']['code'] !== 200 ){
			return new KlinkError('http_request_failed', KlinkHelpers::localize($result['response']['message']));
		}

		if($result['headers']['content-type'] !== self::JSON_ENCODING){
			return new KlinkError('http_response_format', KlinkHelpers::localize('Unsupported content encoding from the server.'));
		}

		// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

		$class = is_object($expected_return_type) ? get_class($expected_return_type) : $expected_return_type;

		return self::_deserialize_array($result['body'], $class);

	}

	/**
	 * Makes a POST request with the specified data using json content.
	 * 
	 * Given the nature of the POST request a non-empty 
	 * 
	 * The expected return is a json response. The response will be automatically mapped to a specified class
	 * 
	 * @param string $url 
	 * @param array|object $data the data that will be sent in the body of the request (json encoded)
	 * @param string|class expected_return_type the class that represents the returned information from the server. If a class is provided must be an instance. If a string is provided the correspondent class must have a constructor with no arguments
	 * @param array $params 
	 * @return object|KlinkError and instance of the class specified in $expected_return_type with the fields setup to what is in the response 
	 */
	function post( $url, $data, $expected_return_type, array $params = null ){
		
		if(!self::_check_expected_return_type($expected_return_type)){
			return new KlinkError('class_expected', KlinkHelpers::localize('The specified return type is not a class.'));
		}

		$url = self::_construct_url($this->baseApiUrl, $url);

		$result = $this->rest->post( $url, 
			array(
				'body' => json_encode($data), 
				'headers' => 'Content-Type:' . self::JSON_ENCODING
			) );

		if(KlinkHelpers::is_error($result)){
			return $result;
		}


		//204 no content
		//201 created
		//202 accepted

		if($result['response']['code'] !== 200 && $result['response']['code'] !== 201){
			return new KlinkError('http_request_failed', KlinkHelpers::localize($result['response']['message']));
		}

		if($result['headers']['content-type'] !== self::JSON_ENCODING){
			return new KlinkError('http_response_format', KlinkHelpers::localize('Unsupported content encoding from the server.'));
		}

		// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

		$class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

		return self::_deserialize_single($result['body'], $class);

	}

	/**
	 * Description
	 * @param string $url 
	 * @param array|object $data the data that will be sent in the body of the request (json encoded)
	 * @param null|string|class $expected_return_type the class that represents the returned information from the server. If a class is provided must be an instance. If a string is provided the correspondent class must have a constructor with no arguments. If null is provided the response must return an empty body or an HTTP status 204 no-content otherwise an error is returned.
	 * @param array $params (reserved for future use)
	 * @return boolean|object|KlinkError
	 */
	function put( $url, $data, $expected_return_type = null, array $params = null ){

		if(!is_null($expected_return_type) && !self::_check_expected_return_type($expected_return_type)){
			return new KlinkError('class_expected', KlinkHelpers::localize('The specified return type is not a class.'));
		}

		$url = self::_construct_url($this->baseApiUrl, $url);

		$result = $this->rest->post( $url, 
			array(
				'body' => json_encode($data), 
				'headers' => 'Content-Type:' . self::JSON_ENCODING
			) );

		if(KlinkHelpers::is_error($result)){
			return $result;
		}


		//204 no content
		//201 created
		//202 accepted

		if($result['response']['code'] !== 200 && $result['response']['code'] !== 204){
			return new KlinkError('http_request_failed', KlinkHelpers::localize($result['response']['message']));
		}

		if(!is_null($expected_return_type)){

			if($result['headers']['content-type'] !== self::JSON_ENCODING){
				return new KlinkError('http_response_format', KlinkHelpers::localize('Unsupported content encoding from the server.'));
			}

			// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

			$class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

			return self::_deserialize_single($result['body'], $class);

		}
		else {
			return true;
		}

		

	}

	/**
	 * Description
	 * @param string $url 
	 * @param array $params (reserved for future version)
	 * @return type
	 */
	function delete( $url, array $params = null ){

		/**
		TODO: handle get parameters
		*/


		if(!self::_check_expected_return_type($expected_return_type)){
			return new KlinkError('class_expected', KlinkHelpers::localize('The specified return type is not a class.'));
		}


		$url = self::_construct_url($this->baseApiUrl, $url);

		$result = $this->rest->delete( $url );

		if(KlinkHelpers::is_error($result)){
			return $result;
		}

		//204 no content
		//201 created
		//202 accepted

		if( $result['response']['code'] !== 200 && $result['response']['code'] !== 204 ){
			return new KlinkError('http_request_failed', KlinkHelpers::localize($result['response']['message']));
		}

		if($result['headers']['content-type'] !== self::JSON_ENCODING){
			return new KlinkError('http_response_format', KlinkHelpers::localize('Unsupported content encoding from the server.'));
		}

		// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

		// $class = is_object($expected_return_type) ? $expected_return_type : new $expected_return_type;

		return true;

	}


	function fileSend(){
		//A specific file post to the server
		/**
			TODO: fileSend specif post
		*/	
	}



	/**
	 * Compose the url given tha base and the portions that needs to be attached
	 * @param string $base the base part of the url, must be a valid url
	 * @param string|array $rest 
	 * @return string the full url
	 */
	private function _construct_url($base, $rest)
	{
		/**
		 TODO: test if $base is a valid url (@parse_url())
		 * */

		if(is_array($rest)){
			$rest = implode('/', $rest);
		}

		return $base . '/' . $rest;
	}

	private function _check_expected_return_type($return_type)
	{
		if( (!is_object($return_type) && !is_string($return_type)) || is_null($return_type) ){
			return false;
		}

		if(!is_object($return_type) && !@class_exists($return_type)){
			return false;
		}

		return true;
	}


	private function _deserialize_single($json, $class)
	{

		try{

			$decoded = json_decode($json, true);
			
			$deserialized = $this->jm->map($decoded, $class);
			return $deserialized;

		}
		catch(Exception $je){

			return new KlinkError('deserialization_error', $je->getMessage());

		}
		catch(\Exception $je){

			return new KlinkError('deserialization_error', $je->getMessage());

		}

	}

	private function _deserialize_array($json, $class)
	{

		try{

			$decoded = json_decode($json, true);
			
			$deserialized = $this->jm->mapArray($decoded, new ArrayObject(), $class);
			return $deserialized;

		}
		catch(Exception $je){

			return new KlinkError('deserialization_error', $je->getMessage());

		}
		catch(\Exception $je){

			return new KlinkError('deserialization_error', $je->getMessage());

		}
		
	}
}



?>