<?php

/**
* Contains a collection of data validators that are used to validate input in the Boilerplate classes
* @deprecated 2.3.0 use KlinkHelpers instead
*/
final class KlinkValidators
{
	
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

			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

		

		if( !empty( $value ) && !is_string( $value ) ){

			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );
			
		}
	}

	public static function is_valid_date_string( $value, $parameter_name, $error_message_format = 'The %s must be formatted as spaecified by the RFC3339' )
	{

		self::is_string_and_not_empty( $url, $parameter_name, $error_message_format );

		// DateTime::RFC3339

		$dt = date_create( $value );

		if($dt !== false) {
			$formatted = $dt->format(DateTime::RFC3339);

			if( $fromatted !== $value ){

				$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

				throw new InvalidArgumentException( $message );

			}
			

		}
		else {
			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );
		}

	}

	/**
	 * Check if the url is syntactically well formed.
	 * @param string $url 
	 * @param string $parameter_name 
	 * @param string $error_message_format 
	 * @throws IllegalArgumentException if the url is not well formatted or null or empty is given
	 */
	public static function is_valid_url($url, $parameter_name, $error_message_format = 'The %s must be a valid url')
	{

		self::is_string_and_not_empty( $url, $parameter_name, $error_message_format );

		if( @parse_url($url) === false ) {

			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

		if (! filter_var($url, FILTER_VALIDATE_URL)) {
			
			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

	}

	public static function is_valid_id( $id, $parameter_name, $error_message_format = 'The %s must be a valid id. Valid ids are composed by alpha-numeric characters with no dashes, underscore, ? and spaces.' )
	{

		self::is_string_and_not_empty( $id, $parameter_name, $error_message_format );

		if ( !preg_match('/^[\w\-\d]+$/', $id) ) {
			
			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name ) );

			throw new InvalidArgumentException( $message );

		}

	}

	/**
	 * Check is an array contains only elements of a particular class
	 * @param array $array the array to check
	 * @param string $classname the class name to check
	 * @throws InvalidArgumentException if an element of the array is not of type specified and is null or contains no elements
	 */
	public static function is_array_of_type( array $array, $classname, $parameter_name, $error_message_format = 'The %s must be a non empty array with all elements of type %s' )
	{

		if( is_null( $array ) || empty( $array ) || empty( $classname ) ){
			
			$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name, $classname ) );

			throw new InvalidArgumentException( $message );

		}

		foreach( $array as $element ){

			if( !is_a( $element, $classname ) ) {

				$message = KlinkHelpers::localize( sprintf( $error_message_format, $parameter_name, $classname ) );

				throw new InvalidArgumentException( $message );

			}

		}

	}


	public static function is_valid_phonenumber( $value, $parameter_name, $error_message_format = 'The %s must be formatted as spaecified by the RFC3339' ){

	}

	public static function is_valid_mail( $value, $parameter_name, $error_message_format = 'The %s must be formatted as spaecified by the RFC3339' ){
		
	}


}