<?php

/**
 * Define the available types of search.
 *
 * @package Klink
 */
final class KlinkVisibilityType
{

	/**
	 * Private document
	 * 
	 * The document will be available only to the institution that uploaded it
	 */	
	const KLINK_PRIVATE = 'private';

	/**
	 * Public document.
	 * 
	 * The document will be visibile from all the institution in KLink
	 */
	const KLINK_PUBLIC = 'public';


	/**
	 * Perform a parse of the given string into a visibility constant
	 * @param string $string the value to be transformed into a KlinkVisibilityType
	 * @return KlinkVisibilityType
	 * @throws InvalidArgumentException if the passed string is not a valid visibility
	 */
	public static function fromString( $string ){

		//if(!self::isValidValue())

		if( $string === self::KLINK_PRIVATE ){
			return KlinkVisibilityType::KLINK_PRIVATE;
		}
		elseif ( $string === self::KLINK_PUBLIC ) {
			return KlinkVisibilityType::KLINK_PUBLIC;
		}

		throw new InvalidArgumentException("Wrong enumeration value");
		

	}


	// methods for recreating an Enumeration

	// private static $constCacheArray = NULL;

 //    private static function getConstants() {
 //        if (self::$constCacheArray == NULL) {
 //            self::$constCacheArray = [];
 //        }
 //        $calledClass = get_called_class();
 //        if (!array_key_exists($calledClass, self::$constCacheArray)) {
 //            $reflect = new ReflectionClass($calledClass);
 //            self::$constCacheArray[$calledClass] = $reflect->getConstants();
 //        }
 //        return self::$constCacheArray[$calledClass];
 //    }

 //    public static function isValidName($name, $strict = false) {
 //        $constants = self::getConstants();

 //        if ($strict) {
 //            return array_key_exists($name, $constants);
 //        }

 //        $keys = array_map('strtolower', array_keys($constants));
 //        return in_array(strtolower($name), $keys);
 //    }

 //    public static function isValidValue($value) {
 //        $values = array_values(self::getConstants());
 //        return in_array($value, $values, $strict = true);
 //    }

}