<?php


/**
* Some Helpers function for performing string operations, checks, encoding,...
*/
class KlinkHelpers
{
	/**
	 * Appends a trailing slash.
	 *
	 * Will remove trailing forward and backslashes if it exists already before adding
	 * a trailing forward slash. This prevents double slashing a string or path.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @since 1.2.0
	 *
	 * @param string $string What to add the trailing slash to.
	 * @return string String with trailing slash added.
	 */
	public static function trailingslashit( $string ) {
		return self::untrailingslashit( $string ) . '/';
	}

	/**
	 * Removes trailing forward slashes and backslashes if they exist.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @since 2.2.0
	 *
	 * @param string $string What to remove the trailing slashes from.
	 * @return string String without the trailing slashes.
	 */
	public static function untrailingslashit( $string ) {
		return rtrim( $string, '/\\' );
	}


	/**
	* @see http://wpseek.com/function/mbstring_binary_safe_encoding/
	*/
	public static function mbstring_binary_safe_encoding( $reset = false ) {
		static $encodings = array();
		static $overloaded = null;

		if ( is_null( $overloaded ) )
			$overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );

		if ( false === $overloaded )
			return;

		if ( ! $reset ) {
			$encoding = mb_internal_encoding();
			array_push( $encodings, $encoding );
			mb_internal_encoding( 'ISO-8859-1' );
		}

		if ( $reset && $encodings ) {
			$encoding = array_pop( $encodings );
			mb_internal_encoding( $encoding );
		}
	}

	/**
	* @see http://wpseek.com/function/reset_mbstring_encoding/
	*/
	public static function reset_mbstring_encoding() {
		self::mbstring_binary_safe_encoding( true );
	}


	/**
	 * Retrieve the description for the HTTP status.
	 *
	 * @since 2.3.0
	 *
	 * @param int $code HTTP status code.
	 * @return string Empty string if not found, or description if found.
	 */
	public static function get_status_header_desc( $code ) {


		$code = self::absint( $code );

			$header_to_desc = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				102 => 'Processing',

				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				207 => 'Multi-Status',
				226 => 'IM Used',

				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => 'Reserved',
				307 => 'Temporary Redirect',

				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				418 => 'I\'m a teapot',
				422 => 'Unprocessable Entity',
				423 => 'Locked',
				424 => 'Failed Dependency',
				426 => 'Upgrade Required',
				428 => 'Precondition Required',
				429 => 'Too Many Requests',
				431 => 'Request Header Fields Too Large',

				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',
				506 => 'Variant Also Negotiates',
				507 => 'Insufficient Storage',
				510 => 'Not Extended',
				511 => 'Network Authentication Required',
			);
		

		if ( isset( $header_to_desc[$code] ) )
			return $header_to_desc[$code];
		else
			return '';
	}

	/**
	 * Set HTTP status header.
	 *
	 * @since 2.0.0
	 *
	 * @see get_status_header_desc()
	 *
	 * @param int $code HTTP status code.
	 */
	public static function status_header( $code ) {
		$description = get_status_header_desc( $code );

		if ( empty( $description ) )
			return;

		$protocol = $_SERVER['SERVER_PROTOCOL'];
		if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
			$protocol = 'HTTP/1.0';
		$status_header = "$protocol $code $description";
		if ( function_exists( 'apply_filters' ) )

			/**
			 * Filter an HTTP status header.
			 *
			 * @since 2.2.0
			 *
			 * @param string $status_header HTTP status header.
			 * @param int    $code          HTTP status code.
			 * @param string $description   Description for the status code.
			 * @param string $protocol      Server protocol.
			 */
			$status_header = apply_filters( 'status_header', $status_header, $code, $description, $protocol );

		@header( $status_header, true, $code );
	}

	/**
	 * Get the header information to prevent caching.
	 *
	 * The several different headers cover the different ways cache prevention
	 * is handled by different browsers
	 *
	 * @since 2.8.0
	 *
	 * @return array The associative array of header names and field values.
	 */
	public static function get_nocache_headers() {
		$headers = array(
			'Expires' => 'Wed, 11 Jan 1984 05:00:00 GMT',
			'Cache-Control' => 'no-cache, must-revalidate, max-age=0',
			'Pragma' => 'no-cache',
		);

		if ( function_exists('apply_filters') ) {
			/**
			 * Filter the cache-controlling headers.
			 *
			 * @since 2.8.0
			 *
			 * @see wp_get_nocache_headers()
			 *
			 * @param array $headers {
			 *     Header names and field values.
			 *
			 *     @type string $Expires       Expires header.
			 *     @type string $Cache-Control Cache-Control header.
			 *     @type string $Pragma        Pragma header.
			 * }
			 */
			$headers = (array) apply_filters( 'nocache_headers', $headers );
		}
		$headers['Last-Modified'] = false;
		return $headers;
	}

	/**
	 * Set the headers to prevent caching for the different browsers.
	 *
	 * Different browsers support different nocache headers, so several
	 * headers must be sent so that all of them get the point that no
	 * caching should occur.
	 *
	 * @since 2.0.0
	 *
	 * @see wp_get_nocache_headers()
	 */
	public static function nocache_headers() {
		$headers = wp_get_nocache_headers();

		unset( $headers['Last-Modified'] );

		// In PHP 5.3+, make sure we are not sending a Last-Modified header.
		if ( function_exists( 'header_remove' ) ) {
			@header_remove( 'Last-Modified' );
		} else {
			// In PHP 5.2, send an empty Last-Modified header, but only as a
			// last resort to override a header already sent. #WP23021
			foreach ( headers_list() as $header ) {
				if ( 0 === stripos( $header, 'Last-Modified' ) ) {
					$headers['Last-Modified'] = '';
					break;
				}
			}
		}

		foreach( $headers as $name => $field_value )
			@header("{$name}: {$field_value}");
	}


	/**
	 * Check whether variable is a Klink Error.
	 *
	 * Returns true if $thing is an object of the KlinkError class.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed $thing Check if unknown variable is a KlinkError object.
	 * @return bool True, if KlinkError. False, if not KlinkError.
	 */
	public static function is_error($thing) {
		
		if ( is_object($thing) && is_a($thing, 'KlinkError') )
			return true;
		
		return false;

	}

	/**
	 * Localize the specified string in a different language. The string must be passed in english like for gettext.
	 * 
	 * if the KLINK_LANGUAGE constant is defined the $lang parameter will be ignored.
	 * 
	 * @param string $string the string to be localized
	 * @param string $lang the language of the localization, if not specified the english localization is returned.
	 * @return string the localized string
	 */
	public static function localize( $string, $lang = 'en' )
	{

		/**
			TODO: gettext implementation
		*/

		if( defined( 'KLINK_LANGUAGE' ) ){

			$constantValue = KLINK_LANGUAGE;

			if( !empty( $constantValue ) && is_string( $constantValue ) ){

				$lang = KLINK_LANGUAGE;
			}
		}

		return $string;

	}

	/**
	 * Check if a string ends with the specified string
	 * @param  string $haystack the string to be checked
	 * @param  string $needle the ending string to check
	 * @return boolean true if $needle is the last part of the $haystack
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 */
	public static function string_ends_with( $haystack, $needle ){

		self::is_string_and_not_empty($haystack, 'haystack');

		if(!is_string($needle)){
			return false;
		}
		
	    $length = strlen($needle);
	    if ($length == 0) {
	        return true;
	    }

	    return (substr($haystack, -$length) === $needle);
		

	}


	// ---- Parameter preconditions check

	/**
	 * Check is the passed value is a non null, non empty string. To enforce the precondition an exception is thrown.
	 * 
	 * @param string $value the value to check
	 * @param string $parameter_name the human understandable name of the parameter to be used in the error message
	 * @param string $error_message_format only one %s is allowed, plese take into account that the format must be in english and will be localized in other languages
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 */
	public static function is_string_and_not_empty( $value, $parameter_name, $error_message_format = 'The %s must be a non empty or null string' )
	{
		if( !empty($value) )
			$value = trim($value);


		if( empty( $value ) ){

			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

		

		if( !empty( $value ) && !is_string( $value ) ){

			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );
			
		}
	}

	/**
	 * Check if the passed date is a valid date and is formatted according to the RFC3339.
	 * 
	 * @param string $value the value to check
	 * @param string $parameter_name the human understandable name of the parameter to be used in the error message
	 * @param string $error_message_format only one %s is allowed, plese take into account that the format must be in english and will be localized in other languages
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 */
	public static function is_valid_date_string( $value, $parameter_name, $error_message_format = 'The %s must be formatted as specified by the RFC3339' )
	{

		self::is_string_and_not_empty( $value, $parameter_name, $error_message_format );

		$dt = date_create( $value );

		if($dt !== false) {
			$formatted = $dt->format(DateTime::RFC3339);

			if( $formatted !== $value ){

				$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

				throw new InvalidArgumentException( $message );

			}
			
		}
		else {
			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );
		}

	}

	/**
	 * Test if the url is syntactically well formed.
	 * 
	 * @param string $value the value to check
	 * @param string $parameter_name the human understandable name of the parameter to be used in the error message
	 * @param string $error_message_format only one %s is allowed, plese take into account that the format must be in english and will be localized in other languages
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 */
	public static function is_valid_url($url, $parameter_name, $error_message_format = 'The %s must be a valid url')
	{

		self::is_string_and_not_empty( $url, $parameter_name, $error_message_format );

		if( @parse_url($url) === false ) {

			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

		if (! filter_var($url, FILTER_VALIDATE_URL)) {
			
			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

	}

	/**
	 * Check if the specified identifier is a syntactically valid KLink identifier.
	 * 
	 * Test the specified identifier if is a syntactically valid KLink Identifier. 
	 * A Klink identifier is valid if composed by letters, numbers and dash ([A-Za-z0-9\-]).
	 * 
	 * @param string $id the value to check
	 * @param string $parameter_name the human understandable name of the parameter to be used in the error message
	 * @param string $error_message_format only one %s is allowed, plese take into account that the format must be in english and will be localized in other languages
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 */
	public static function is_valid_id( $id, $parameter_name, $error_message_format = 'The %s must be a valid id. Valid ids are composed by alpha-numeric characters with no dashes, underscore, ? and spaces.' )
	{

		self::is_string_and_not_empty( $id, $parameter_name, $error_message_format );

		if ( !preg_match('/^[\w\-\d]+$/', $id) ) {
			
			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

	}

	/**
	 * Check is an array contains only elements of a particular class.
	 * 
	 * @param array $array the array to check
	 * @param string $classname the class name to check
	 * @throws InvalidArgumentException if an element of the array is not of type specified and is null or contains no elements
	 */
	public static function is_array_of_type( array $array, $classname, $parameter_name, $error_message_format = 'The %s must be a non empty array with all elements of type %s' )
	{

		if( is_null( $array ) || empty( $array ) || empty( $classname ) ){
			
			$message = self::localize( sprintf( $error_message_format, $parameter_name, $classname ) );

			throw new InvalidArgumentException( $message );

		}

		foreach( $array as $element ){

			if( !is_a( $element, $classname ) ) {

				$message = self::localize( sprintf( $error_message_format, $parameter_name, $classname ) );

				throw new InvalidArgumentException( $message );

			}

		}

	}

	/**
	 * Check if the specified phone number is in an acceptable format.
	 * 
	 * @param string $value the value to check
	 * @param string $parameter_name the human understandable name of the parameter to be used in the error message
	 * @param string $error_message_format only one %s is allowed, plese take into account that the format must be in english and will be localized in other languages
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 * @see http://stackoverflow.com/questions/123559/a-comprehensive-regex-for-phone-number-validation
	 */
	public static function is_valid_phonenumber( $value, $parameter_name, $error_message_format = 'The %s is not recognized as valid phone number' ){

		self::is_string_and_not_empty( $value, $parameter_name, $error_message_format );


		if( filter_var( $value, FILTER_VALIDATE_EMAIL) && @parse_url($value) !== false && filter_var($value, FILTER_VALIDATE_URL) ) {

			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

		if ( !preg_match('/.*([0-9])+.*/i', $value) ){

				$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

				throw new InvalidArgumentException( $message );

		}

		// print_r( var_export( preg_match('/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i', $value), true) );
		//die();

		// if ( !preg_match('/^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i', $value) ){
			
		// 	$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

		// 	throw new InvalidArgumentException( $message );

		// }

		

	}

	/**
	 * Check if the email address specified is syntactically correct.
	 * 
	 * @param string $value the value to check
	 * @param string $parameter_name the human understandable name of the parameter to be used in the error message
	 * @param string $error_message_format only one %s is allowed, plese take into account that the format must be in english and will be localized in other languages
	 * @throws InvalidArgumentException if the passed value is empty or null or is not a string
	 */
	public static function is_valid_mail( $value, $parameter_name, $error_message_format = 'The %s must be a valid email address' ){
		
		self::is_string_and_not_empty( $value, $parameter_name, $error_message_format );

		if (!filter_var( $value, FILTER_VALIDATE_EMAIL) ) {
		    
			$message = self::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

	}


	// ---- Dates

	/**
	 * The current date and time formatted as RFC3339
	 * @return string
	 */
	public static function now(){
		$dt = date_create();

		return $dt->format(DateTime::RFC3339);
	}

	/**
	 * Format a date according to the RFC3339
	 * @param  string $a_date the source date. Must be compatible to dates accepted by date_create
	 * @see date_create
	 * @return string
	 */
	public static function format_date( $a_date ){

		$dt = date_create( $a_date );

		if($dt===false){
			throw new InvalidArgumentException('Invalid date passed');
			
		}

		$f = $dt->format(DateTime::RFC3339);

		return $f;

	}


	// ---- Input sanitation

	/**
	 * Cast a string to integer and make it positive
	 * @param  string $maybeint
	 * @return int
	 */
	public static function absint( $maybeint ) {
		return abs( intval( $maybeint ) );
	}

	/**
	 * Sanitize the input string.
	 *
	 * Perform security sanitation on the string and ensure the UTF-8 character encoding.
	 * The performed conversion includes:
	 * - iconv
	 * - filter_var
	 * - htmlentities
	 * 
	 * @param  string $value the string to sanitize
	 * @return string the sanitized string
	 */
	public static function sanitize_string( $value )
	{
		# code...

		$value = iconv( 'UTF-8', 'UTF-8//IGNORE', $value );

		$value = filter_var( $value, FILTER_SANITIZE_STRING );

		return htmlentities( $value, ENT_QUOTES, 'UTF-8', false );
	}


}