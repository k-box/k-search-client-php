<?php namespace Klink\Utils;


/**
* 
*/
class Helpers
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


	public static function absint( $maybeint ) {
		return abs( intval( $maybeint ) );
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
	function is_error($thing) {
		if ( is_object($thing) && is_a($thing, 'Klink\KlinkError') )
			return true;
		return false;
	}


	function localize($string){
		return $string;
	}


}