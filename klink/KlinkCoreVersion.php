<?php

/**
 * Define the available K-Link Core API versions as constants.
 *
 * @package Klink
 * @since 3.0.0
 */
final class KlinkCoreVersion
{

	/**
	 * Version 2
	 * 
	 * a shortcut to {@see KlinkCoreVersion::V2_1}
	 */	
	const V2 = '2.1';

	/**
	 * Version 2.1 
	 * 
	 * The original KCore
	 */	
	const V2_1 = '2.1';

	/**
	 * Version 2.2.
	 * 
	 * The new version that supports OR, AND, Projects,...
	 */
	const V2_2 = '2.2';


	/**
	 * Perform a parse of the given string into a visibility constant
	 * @param string $string the value to be transformed into a KlinkVisibilityType
	 * @return KlinkVisibilityType
	 * @throws InvalidArgumentException if the passed string is not a valid visibility
	 */
	public static function fromString( $string ){

		if( $string === self::V2 || $string === self::V2_1 ){
			return self::V2_1;
		}
		elseif ( $string === self::V2_2 ) {
			return self::V2_2;
		}

		throw new InvalidArgumentException("KCore version $string is unknown or invalid", 101);
		

	}

}