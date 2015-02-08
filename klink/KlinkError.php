<?php

/**
 * Klink Error API.
 *
 * Contains the KlinkError class and the is_klink_error() function.
 *
 * @package Klink
 */

/**
 * Klink Error class.
 *
 * Container for checking for Klink Adapter errors and error messages. Return
 * KlinkError and use {@link is_klink_error()} to check if this class is returned.
 * Many core Klink Adapter functions pass this class in the event of an error and
 * if not handled properly will result in code errors.
 *
 * @package Klink
 * @since 0.1.0
 */
final class KlinkError {

	/**
	 * Class information expected for deserialization purposes
	 */
	const ERROR_CLASS_EXPECTED = 'class_expected';
	const ERRORCODE_CLASS_EXPECTED = 48;

	const ERROR_DESERIALIZATION_ERROR = 'deserialization_error';
	const ERRORCODE_DESERIALIZATION_ERROR = 49;

	/**
	 * Failed HTTP request
	 */
	const ERROR_HTTP_REQUEST_FAILED = 'http_request_failed';
	const ERRORCODE_HTTP_REQUEST_FAILED = 1516;

	const ERROR_CONNECTION_REFUSED = 'http_connection_refused';
	const ERRORCODE_CONNECTION_REFUSED = 1517;
	
	/**
	 * Wrong response format
	 */
	const ERROR_HTTP_RESPONSE_FORMAT = 'http_response_format';
	const ERRORCODE_HTTP_RESPONSE_FORMAT = 2342;

	const ERROR_HTTP_FAILURE = 'http_failure';
	const ERRORCODE_HTTP_FAILURE = 108;


	const ERROR_HTTP_REQUEST_SSL_FAILED = 'http_request_ssl_failed';
	const ERROR_HTTP_REQUEST_TIMEOUT = 'http_request_timeout';
	const ERRORCODE_HTTP_REQUEST_SSL_FAILED = 801;
	const ERRORCODE_HTTP_REQUEST_TIMEOUT = 999;

	const ERROR_HTTP_REQUEST_FAILED_TOO_MANY_REDIRECTS = 'http_request_failed_too_many_redirects';
	const ERRORCODE_HTTP_REQUEST_FAILED_TOO_MANY_REDIRECTS = 99;


	/**
	 * Stores the list of errors.
	 *
	 * @since 0.1.0
	 * @var array
	 * @access private
	 */
	private $errors = array();

	/**
	 * Stores the list of data for error codes.
	 *
	 * @since 0.1.0
	 * @var array
	 * @access private
	 */
	private $error_data = array();

	/**
	 * Initialize the error.
	 *
	 * If `$code` is empty, the other parameters will be ignored.
	 * When `$code` is not empty, `$message` will be used even if
	 * it is empty. The `$data` parameter will be used only if it
	 * is not empty.
	 *
	 * Though the class is constructed with a single error code and
	 * message, multiple codes can be added using the `add()` method.
	 *
	 * @since 0.1.0
	 *
	 * @param string|int $code Error code
	 * @param string $message Error message
	 * @param mixed $data Optional. Error data.
	 * @return KlinkError
	 */
	public function __construct( $code = '', $message = '', $data = '' ) {
		if ( empty($code) )
			return;

		$this->errors[$code][] = $message;

		if ( ! empty($data) )
			$this->error_data[$code] = $data;
	}

	/**
	 * Make private properties readable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name Property to get.
	 * @return mixed Property.
	 */
	public function __get( $name ) {
		return $this->$name;
	}

	/**
	 * Make private properties settable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name  Property to set.
	 * @param mixed  $value Property value.
	 * @return mixed Newly-set property.
	 */
	public function __set( $name, $value ) {
		return $this->$name = $value;
	}

	/**
	 * Make private properties checkable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name Property to check if set.
	 * @return bool Whether the property is set.
	 */
	public function __isset( $name ) {
		return isset( $this->$name );
	}

	/**
	 * Make private properties un-settable for backwards compatibility.
	 *
	 * @since 4.0.0
	 * @access public
	 *
	 * @param string $name Property to unset.
	 */
	public function __unset( $name ) {
		unset( $this->$name );
	}

	/**
	 * Retrieve all error codes.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return array List of error codes, if available.
	 */
	public function get_error_codes() {
		if ( empty($this->errors) )
			return array();

		return array_keys($this->errors);
	}

	/**
	 * Retrieve first error code available.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return string|int Empty string, if no error codes.
	 */
	public function get_error_code() {
		$codes = $this->get_error_codes();

		if ( empty($codes) )
			return '';

		return $codes[0];
	}

	/**
	 * Retrieve all error messages or error messages matching code.
	 *
	 * @since 0.1.0
	 *
	 * @param string|int $code Optional. Retrieve messages matching code, if exists.
	 * @return array Error strings on success, or empty array on failure (if using code parameter).
	 */
	public function get_error_messages($code = '') {
		// Return all messages if no code specified.
		if ( empty($code) ) {
			$all_messages = array();
			foreach ( (array) $this->errors as $code => $messages )
				$all_messages = array_merge($all_messages, $messages);

			return $all_messages;
		}

		if ( isset($this->errors[$code]) )
			return $this->errors[$code];
		else
			return array();
	}

	/**
	 * Get single error message.
	 *
	 * This will get the first message available for the code. If no code is
	 * given then the first code available will be used.
	 *
	 * @since 0.1.0
	 *
	 * @param string|int $code Optional. Error code to retrieve message.
	 * @return string
	 */
	public function get_error_message($code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();
		$messages = $this->get_error_messages($code);
		if ( empty($messages) )
			return '';
		return $messages[0];
	}

	/**
	 * Retrieve error data for error code.
	 *
	 * @since 0.1.0
	 *
	 * @param string|int $code Optional. Error code.
	 * @return mixed Null, if no errors.
	 */
	public function get_error_data($code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();

		if ( isset($this->error_data[$code]) )
			return $this->error_data[$code];
		return null;
	}

	public function get_error_data_code($code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();

		if ( isset($this->error_data[$code]) )
			return (int)$this->error_data[$code];
		return 0;
	}

	/**
	 * Add an error or append additional message to an existing error.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @param string|int $code Error code.
	 * @param string $message Error message.
	 * @param mixed $data Optional. Error data.
	 */
	public function add($code, $message, $data = '') {
		$this->errors[$code][] = $message;
		if ( ! empty($data) )
			$this->error_data[$code] = $data;
	}

	/**
	 * Add data for error code.
	 *
	 * The error code can only contain one error data.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $data Error data.
	 * @param string|int $code Error code.
	 */
	public function add_data($data, $code = '') {
		if ( empty($code) )
			$code = $this->get_error_code();

		$this->error_data[$code] = $data;
	}


	public function __toString()
    {
        return implode( '.', $this->get_error_messages() );
    }

}

